<?php
/**
 * Скрипт автообновления товаров и категорий из YML фида
 * Запуск по крону: php /path/to/price/import/import.php
 *
 * Сопоставление по полю model (артикул)
 * Пакетная обработка (batch) для больших файлов
 * Товары не в фиде и с quantity=0 -> status=0
 */

// Подключаем конфигурацию
require_once __DIR__ . '/config.php';

/** @var mysqli */
$_db = null;

function log_msg($msg) {
    static $log_file = null;
    
    if ($log_file === null) {
        $now = time();
        $date_dir = date('d_m_Y', $now);
        $time_prefix = date('H_i', $now);
        $dir_path = LOG_DIR . '/' . $date_dir;
        $log_file = $dir_path . '/' . $time_prefix . '_' . $date_dir . '.log';
        
        if (!is_dir($dir_path)) {
            if (!mkdir($dir_path, 0755, true)) {
                $log_file = false;
                $err_msg = "Ошибка: не удалось создать директорию для логов: {$dir_path}";
                if (PHP_SAPI === 'cli') {
                    echo "[{$err_msg}]\n";
                } else {
                    echo htmlspecialchars($err_msg) . "<br>\n";
                }
            }
        }
    }

    $date = date('Y-m-d H:i:s');
    if ($log_file !== false) {
        file_put_contents($log_file, "[{$date}] {$msg}\n", FILE_APPEND | LOCK_EX);
    }
    
    // В веб-режиме выводим с <br> для переноса строк в браузере
    if (PHP_SAPI !== 'cli') {
        echo "[{$date}] " . htmlspecialchars($msg) . "<br>\n";
    } else {
        echo "[{$date}] {$msg}\n";
    }
}

function db() {
    global $_db;
    if ($_db === null) {
        $_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($_db->connect_error) {
            log_msg("Ошибка подключения к БД: " . $_db->connect_error);
            exit(1);
        }
        $_db->set_charset(DB_CHARSET);
        $_db->query("SET NAMES utf8");
        $_db->query("SET SQL_MODE = ''");
    }
    return $_db;
}

function q($sql) {
    $r = db()->query($sql);
    if (db()->error) {
        log_msg("DB Error: " . db()->error . " SQL: " . substr($sql, 0, 200));
    }
    return $r;
}

function esc($str) {
    return db()->real_escape_string((string)$str);
}

function get_languages() {
    $result = q("SELECT language_id, code, name FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order, name");
    $langs = [];
    while ($row = $result->fetch_assoc()) {
        $langs[] = $row;
    }
    return $langs;
}

function load_feed() {
    // Если FEED_SOURCE - URL, скачиваем с кешированием
    if (strpos(FEED_SOURCE, 'http://') === 0 || strpos(FEED_SOURCE, 'https://') === 0) {
        // Проверяем кеш
        if (file_exists(FEED_CACHE_FILE) && (time() - filemtime(FEED_CACHE_FILE)) < FEED_CACHE_TTL) {
            log_msg("Загрузка фида из кеша: " . FEED_CACHE_FILE);
            return file_get_contents(FEED_CACHE_FILE);
        }
        log_msg("Скачивание фида: " . FEED_SOURCE);
        $data = @file_get_contents(FEED_SOURCE);
        if ($data === false) {
            // Пробуем через curl
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, FEED_SOURCE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($http_code != 200) {
                log_msg("ОШИБКА: HTTP {$http_code} при скачивании фида");
                return null;
            }
        }
        if (empty($data)) {
            log_msg("ОШИБКА: Пустой ответ от фида");
            return null;
        }
        file_put_contents(FEED_CACHE_FILE, $data);
        log_msg("Фид сохранён в кеш (" . strlen($data) . " байт)");
        return $data;
    }

    // Локальный файл
    if (!file_exists(FEED_SOURCE)) {
        log_msg("ОШИБКА: Файл фида не найден: " . FEED_SOURCE);
        return null;
    }
    log_msg("Загрузка фида из файла: " . FEED_SOURCE);
    return file_get_contents(FEED_SOURCE);
}

/**
 * Скачать изображение с URL и сохранить локально
 * @param string $url URL изображения
 * @return string относительный путь от папки image/ или пустая строка
 */
function download_image($url) {
    if (empty($url)) return '';

    // Извлекаем путь из URL вида: .../image/catalog/... → catalog/...
    // или любой другой URL → import/{hash}.jpg
    $rel_path = '';
    if (preg_match('#/image/(.+)$#', $url, $m)) {
        $rel_path = $m[1]; // catalog/import/1/img/file.jpg
    } else {
        // Если URL не содержит /image/ — сохраняем с хешем
        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        $ext = $ext ?: 'jpg';
        $rel_path = 'import/' . md5($url) . '.' . $ext;
    }

    $local_path = IMAGE_DIR . $rel_path;
    $local_dir = dirname($local_path);

    // Если уже есть — возвращаем путь
    if (file_exists($local_path)) {
        return $rel_path;
    }

    // Создаём директорию
    if (!is_dir($local_dir)) {
        if (!mkdir($local_dir, 0755, true)) {
            log_msg("  Не удалось создать директорию: {$local_dir}");
            return '';
        }
    }

    // Скачиваем
    $data = @file_get_contents($url);
    if ($data === false) {
        // Пробуем curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code != 200 || empty($data)) {
            log_msg("  Не удалось скачать: {$url} (HTTP {$http_code})");
            return '';
        }
    }

    if (file_put_contents($local_path, $data) === false) {
        log_msg("  Не удалось записать файл: {$local_path}");
        return '';
    }

    return $rel_path;
}

/**
 * Транслитерация украинских/русских символов в латиницу для SEO URL
 */
function transliterate($str) {
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g',
        'д' => 'd', 'е' => 'e', 'є' => 'ye', 'ж' => 'zh', 'з' => 'z',
        'и' => 'y', 'і' => 'i', 'ї' => 'yi', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
        'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
        'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
        'ь' => '', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'H', 'Ґ' => 'G',
        'Д' => 'D', 'Е' => 'E', 'Є' => 'Ye', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'Y', 'І' => 'I', 'Ї' => 'Yi', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P',
        'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
        'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
        'Ь' => '', 'Ю' => 'Yu', 'Я' => 'Ya',
        'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'Y', 'э' => 'e', 'Э' => 'E',
        'ё' => 'yo', 'Ё' => 'Yo',
    ];
    $str = strtr($str, $map);
    // заменяем всё кроме букв, цифр, дефиса на дефис
    $str = preg_replace('/[^a-zA-Z0-9\-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    $str = trim($str, '-');
    $str = mb_strtolower($str, 'UTF-8');
    return $str;
}

/**
 * Сгенерировать уникальный SEO URL для товара
 */
function generate_seo_url($product_id, $name, $language_id) {
    $keyword = transliterate($name);
    if (empty($keyword)) {
        $keyword = 'product-' . $product_id;
    }

    // Проверяем уникальность
    $base_keyword = $keyword;
    $i = 1;
    while (true) {
        $check = q("SELECT seo_url_id FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . esc($keyword) . "' AND store_id = '" . STORE_ID . "'");
        if ($check->num_rows == 0) break;
        $keyword = $base_keyword . '-' . $i;
        $i++;
    }

    q("INSERT INTO " . DB_PREFIX . "seo_url SET 
        store_id = '" . STORE_ID . "',
        language_id = '" . (int)$language_id . "',
        query = 'product_id=" . (int)$product_id . "',
        keyword = '" . esc($keyword) . "'");

    return $keyword;
}

/**
 * Сгенерировать уникальный SEO URL для категории
 */
function generate_category_seo_url($category_id, $name, $language_id) {
    $keyword = transliterate($name);
    if (empty($keyword)) {
        $keyword = 'category-' . $category_id;
    }

    // Проверяем уникальность
    $base_keyword = $keyword;
    $i = 1;
    while (true) {
        $check = q("SELECT seo_url_id FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . esc($keyword) . "' AND store_id = '" . STORE_ID . "'");
        if ($check->num_rows == 0) break;
        $keyword = $base_keyword . '-' . $i;
        $i++;
    }

    q("INSERT INTO " . DB_PREFIX . "seo_url SET
        store_id = '" . STORE_ID . "',
        language_id = '" . (int)$language_id . "',
        query = 'category_id=" . (int)$category_id . "',
        keyword = '" . esc($keyword) . "'");

    return $keyword;
}

function get_config_language_id() {
    $result = q("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = 'config_language_id' AND store_id = '0'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return (int)$row['value'];
    }
    // fallback — первый активный язык
    $langs = get_languages();
    return !empty($langs) ? $langs[0]['language_id'] : 1;
}

function get_manufacturer_id($name) {
    $name_esc = esc($name);
    $result = q("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $name_esc . "'");
    $lang_id = get_config_language_id();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $man_id = (int)$row['manufacturer_id'];
        // Обновляем только meta_title и meta_h1, не затираем SEO-поля
        $desc_check = q("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$man_id . "' AND language_id = '" . (int)$lang_id . "'");
        if ($desc_check->num_rows > 0) {
            q("UPDATE " . DB_PREFIX . "manufacturer_description SET 
                meta_title = '" . $name_esc . "',
                meta_h1 = '" . $name_esc . "'
                WHERE manufacturer_id = '" . (int)$man_id . "' AND language_id = '" . (int)$lang_id . "'");
        } else {
            q("INSERT INTO " . DB_PREFIX . "manufacturer_description SET 
                manufacturer_id = '" . (int)$man_id . "',
                language_id = '" . (int)$lang_id . "',
                description = '',
                description3 = '',
                meta_description = '',
                meta_keyword = '',
                meta_title = '" . $name_esc . "',
                meta_h1 = '" . $name_esc . "'");
        }
        return $man_id;
    }
    q("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $name_esc . "', sort_order = '0'");
    $man_id = db()->insert_id;
    q("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$man_id . "', store_id = '" . STORE_ID . "'");
    // Записываем в manufacturer_description с language_id из настроек магазина
    q("INSERT INTO " . DB_PREFIX . "manufacturer_description SET 
        manufacturer_id = '" . (int)$man_id . "',
        language_id = '" . (int)$lang_id . "',
        description = '',
        description3 = '',
        meta_description = '',
        meta_keyword = '',
        meta_title = '" . $name_esc . "',
        meta_h1 = '" . $name_esc . "'");
    return $man_id;
}

function import_feed() {
    $start_time = microtime(true);
    log_msg("=== НАЧАЛО ИМПОРТА ФИДА ===");

    $languages = get_languages();
    if (empty($languages)) {
        log_msg("ОШИБКА: Не найдены языки в БД");
        return;
    }
    $main_lang_id = $languages[0]['language_id'];

    // ======== ЗАГРУЗКА ФИДА ========
    $xml_data = load_feed();
    if ($xml_data === null) return;

    $xml = simplexml_load_string($xml_data);
    if ($xml === false) {
        log_msg("ОШИБКА: Не удалось загрузить XML");
        return;
    }

    log_msg("Фид загружен. Дата: " . $xml['date']);
    $shop = $xml->shop;

    // ======== КАТЕГОРИИ ========
    log_msg("--- Обработка категорий ---");
    $category_map = [];
    $categories_xml = [];

    foreach ($shop->categories->category as $category) {
        $cat_id = (int)$category['id'];
        $parent_id = isset($category['parentId']) ? (int)$category['parentId'] : 0;
        $name = trim((string)$category);
        $categories_xml[$cat_id] = ['parent_id' => $parent_id, 'name' => $name];
    }

    // Загружаем маппинг категорий (если есть)
    $category_mapping = [];
    $map_file = __DIR__ . '/feed_import_mapping.php';
    if (file_exists($map_file)) {
        $category_mapping = require $map_file;
        if (!empty($category_mapping)) {
            log_msg("Загружен маппинг категорий: " . count($category_mapping) . " записей");
        }
    }

    uasort($categories_xml, function($a, $b) {
        if ($a['parent_id'] == 0 && $b['parent_id'] != 0) return -1;
        if ($a['parent_id'] != 0 && $b['parent_id'] == 0) return 1;
        return 0;
    });

    // Существующие категории - загружаем в массив вида "name|parent_id => category_id"
    $existing_cats_map = [];
    $result = q("SELECT c.category_id, cd.name, c.parent_id FROM " . DB_PREFIX . "category c 
        LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id AND cd.language_id = '" . (int)$main_lang_id . "')");
    while ($row = $result->fetch_assoc()) {
        $key = $row['name'] . '|' . (int)$row['parent_id'];
        $existing_cats_map[$key] = (int)$row['category_id'];
    }

    foreach ($categories_xml as $xml_id => $cat_data) {
        // Если для этой категории есть маппинг — используем его напрямую
        if (isset($category_mapping[$xml_id])) {
            $cat_oc_id = (int)$category_mapping[$xml_id];
            $category_map[$xml_id] = $cat_oc_id;
            log_msg("  Маппинг: категория фида ID {$xml_id} ({$cat_data['name']}) → категория сайта ID {$cat_oc_id}");
            continue;
        }

        $parent_oc_id = 0;
        if ($cat_data['parent_id'] > 0 && isset($category_map[$cat_data['parent_id']])) {
            $parent_oc_id = $category_map[$cat_data['parent_id']];
        }

        $key = $cat_data['name'] . '|' . $parent_oc_id;

        if (isset($existing_cats_map[$key])) {
            $cat_oc_id = $existing_cats_map[$key];
            // Проверяем и создаём SEO URL для категории, если его нет (для всех языков)
            $seo_check = q("SELECT seo_url_id FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . (int)$cat_oc_id . "' AND store_id = '" . STORE_ID . "'");
            if ($seo_check->num_rows == 0) {
                foreach ($languages as $lang) {
                    generate_category_seo_url($cat_oc_id, $cat_data['name'], $lang['language_id']);
                }
                log_msg("  Добавлен SEO URL для категории: {$cat_data['name']} (ID: {$cat_oc_id})");
            }
            $category_map[$xml_id] = $cat_oc_id;
        } else {
            $name_esc = esc($cat_data['name']);

            q("INSERT INTO " . DB_PREFIX . "category SET 
                parent_id = '" . (int)$parent_oc_id . "', 
                top = '" . ($parent_oc_id == 0 ? 1 : 0) . "', 
                `column` = '1', 
                sort_order = '0', 
                status = '" . CATEGORY_STATUS . "', 
                date_modified = NOW(), 
                date_added = NOW()");
            $new_cat_id = db()->insert_id;

            foreach ($languages as $lang) {
                q("INSERT INTO " . DB_PREFIX . "category_description SET 
                    category_id = '" . (int)$new_cat_id . "', 
                    language_id = '" . (int)$lang['language_id'] . "', 
                    name = '" . $name_esc . "',
                    meta_title = '" . $name_esc . "',
                    meta_h1 = '" . $name_esc . "'");
            }

            q("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$new_cat_id . "', store_id = '" . STORE_ID . "'");

            $level = 0;
            if ($parent_oc_id > 0) {
                $path_result = q("SELECT * FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$parent_oc_id . "' ORDER BY level ASC");
                while ($path_row = $path_result->fetch_assoc()) {
                    q("INSERT INTO " . DB_PREFIX . "category_path SET category_id = '" . (int)$new_cat_id . "', path_id = '" . (int)$path_row['path_id'] . "', level = '" . (int)$level . "'");
                    $level++;
                }
            }
            q("INSERT INTO " . DB_PREFIX . "category_path SET category_id = '" . (int)$new_cat_id . "', path_id = '" . (int)$new_cat_id . "', level = '" . (int)$level . "'");

            // SEO URL для категории (для всех языков)
            foreach ($languages as $lang) {
                generate_category_seo_url($new_cat_id, $cat_data['name'], $lang['language_id']);
            }

            $category_map[$xml_id] = $new_cat_id;
            log_msg("  Добавлена категория: {$cat_data['name']} (ID: {$new_cat_id})");
        }
    }
    log_msg("Категорий в фиде: " . count($categories_xml) . ", сопоставлено: " . count($category_map));

    // ======== СБОР ТОВАРОВ ========
    log_msg("--- Обработка товаров ---");

    $offers = $shop->offers->offer;
    $total_offers = count($offers);
    log_msg("Всего товаров в фиде: " . $total_offers);

    $feed_models = [];
    foreach ($offers as $offer) {
        $model = trim((string)$offer->model);
        if (!empty($model)) {
            $feed_models[$model] = true;
        }
    }
    log_msg("Собрано model для анализа: " . count($feed_models));

    // ======== ДЕАКТИВАЦИЯ ТОВАРОВ НЕ В ФИДЕ ========
    log_msg("--- Деактивация товаров не в фиде ---");

    $feed_model_list = array_keys($feed_models);
    $total_deactivated = 0;

    if (!empty($feed_model_list)) {
        $escaped_models = array_map(function($m) { return "'" . esc($m) . "'"; }, $feed_model_list);
        $models_in = implode(',', $escaped_models);
        q("UPDATE " . DB_PREFIX . "product SET status = '0', date_modified = NOW() 
           WHERE model NOT IN ({$models_in}) AND status = '1'");
        $total_deactivated = db()->affected_rows;
    }
    
    log_msg("Деактивировано товаров (товаров нет в фиде): " . $total_deactivated);

    // ======== ОБНОВЛЕНИЕ/ДОБАВЛЕНИЕ ТОВАРОВ ========
    log_msg("--- Обновление/добавление товаров ---");

    // Загружаем существующие товары по model
    $existing_products = [];
    $result = q("SELECT product_id, model FROM " . DB_PREFIX . "product");
    while ($row = $result->fetch_assoc()) {
        $existing_products[$row['model']] = (int)$row['product_id'];
    }
    log_msg("Существующих товаров в БД: " . count($existing_products));

    $inserted = 0;
    $updated = 0;
    $batch_data = [];

    //$counter = 0;

    foreach ($offers as $offer) {
        $counter++;
        //if ($counter > 1) break;  // 1 товар для теста

        $model = trim((string)$offer->model);
        if (empty($model)) continue;

        $name = trim((string)$offer->name);
        $name = html_entity_decode($name, ENT_QUOTES, 'UTF-8');
        $price = (float)$offer->price;
        $oldprice = isset($offer->oldprice) ? (float)$offer->oldprice : 0;
        $quantity = isset($offer->quantity) ? (int)$offer->quantity : 0;
        $subtract = isset($offer->subtract) ? (int)$offer->subtract : 1;
        $available = (string)$offer['available'];
        $status = ($available == 'true') ? 1 : 0;
        $description = (string)$offer->description;
        $description = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
        $vendor = trim((string)$offer->vendor);
        $image = trim((string)$offer->picture);

        // Категория
        $cat_xml_id = (int)$offer->categoryId;
        $cat_oc_id = isset($category_map[$cat_xml_id]) ? $category_map[$cat_xml_id] : 0;

        // Производитель
        $man_id = 0;
        if (!empty($vendor)) {
            $man_id = get_manufacturer_id($vendor);
        }

        // Скачиваем все изображения
        $images = [];
        foreach ($offer->picture as $pic) {
            $url = trim((string)$pic);
            $local_pic = download_image($url);
            if (!empty($local_pic)) {
                $images[] = $local_pic;
            }
        }

        // Параметры
        $params = [];
        foreach ($offer->param as $param) {
            $params[(string)$param['name']] = trim((string)$param);
        }

        $sku = isset($params['Код позиции']) ? $params['Код позиции'] : '';
        $isbn = isset($params['ISBN/Штрихкод']) ? $params['ISBN/Штрихкод'] : '';
        $upc = $isbn;
        $ean = $isbn;
        $jan = $isbn;

        $weight = isset($params['Масса']) ? (float)str_replace(',', '.', $params['Масса']) : 0;
        $length = 0; $width = 0; $height = 0;
        if (isset($params['Розмір']) || isset($params['Размер'])) {
            $size_str = isset($params['Розмір']) ? $params['Розмір'] : $params['Размер'];
            $parts = explode('x', $size_str);
            if (count($parts) == 3) {
                $length = (float)trim($parts[0]);
                $width = (float)trim($parts[1]);
                $height = (float)trim($parts[2]);
            }
        }

        // Скачиваем главное изображение
        $main_image = download_image($image);

        $date_available = date('Y-m-d');
        if (isset($params['Дата появления'])) {
            $date_available = date('Y-m-d', strtotime($params['Дата появления']));
        }

        $batch_data[] = [
            'model' => $model,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'oldprice' => $oldprice,
            'quantity' => $quantity,
            'subtract' => $subtract,
            'status' => $status,
            'cat_oc_id' => $cat_oc_id,
            'man_id' => $man_id,
            'main_image' => $main_image,
            'images' => $images,
            'sku' => $sku,
            'upc' => $upc,
            'ean' => $ean,
            'jan' => $jan,
            'isbn' => $isbn,
            'weight' => $weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'date_available' => $date_available,
        ];

        if (count($batch_data) >= BATCH_SIZE) {
            $r = process_batch($batch_data, $existing_products, $languages);
            $inserted += $r['inserted'];
            $updated += $r['updated'];
            $batch_data = [];
        }
    }

    if (!empty($batch_data)) {
        $r = process_batch($batch_data, $existing_products, $languages);
        $inserted += $r['inserted'];
        $updated += $r['updated'];
    }

    $elapsed = round(microtime(true) - $start_time, 2);
    log_msg("=== ИТОГО: добавлено {$inserted}, обновлено {$updated}, деактивировано {$total_deactivated} за {$elapsed}с ===");
}

function process_batch($batch, &$existing_products, $languages) {
    $inserted = 0;
    $updated = 0;
    $main_lang_id = $languages[0]['language_id'];

    foreach ($batch as $item) {
        $model = $item['model'];
        $name_esc = esc($item['name']);
        $desc_esc = esc($item['description']);
        $model_esc = esc($model);
        $sku_esc = esc($item['sku']);
        $upc_esc = esc($item['upc']);
        $ean_esc = esc($item['ean']);
        $jan_esc = esc($item['jan']);
        $isbn_esc = esc($item['isbn']);
        $image_esc = esc($item['main_image']);

        $has_discount = ($item['oldprice'] > 0 && $item['oldprice'] > $item['price']);
        $base_price = $has_discount ? $item['oldprice'] : $item['price'];
        $special_price = $item['price'];

        if (isset($existing_products[$model])) {
            $product_id = $existing_products[$model];

            // Обновление
            q("UPDATE " . DB_PREFIX . "product SET 
                model = '" . $model_esc . "',
                sku = '" . $sku_esc . "',
                upc = '" . $upc_esc . "',
                ean = '" . $ean_esc . "',
                jan = '" . $jan_esc . "',
                isbn = '" . $isbn_esc . "',
                image = '" . $image_esc . "',
                manufacturer_id = '" . (int)$item['man_id'] . "',
                price = '" . (float)$base_price . "',
                quantity = '" . (int)$item['quantity'] . "',
                subtract = '" . (int)$item['subtract'] . "',
                weight = '" . (float)$item['weight'] . "',
                length = '" . (float)$item['length'] . "',
                width = '" . (float)$item['width'] . "',
                height = '" . (float)$item['height'] . "',
                status = '" . (int)$item['status'] . "',
                date_available = '" . esc($item['date_available']) . "',
                date_modified = NOW()
                WHERE product_id = '" . (int)$product_id . "'");

            q("UPDATE " . DB_PREFIX . "product_description SET 
                name = '" . $name_esc . "',
                description = '" . $desc_esc . "',
                meta_title = '" . $name_esc . "',
                meta_h1 = '" . $name_esc . "'
                WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$main_lang_id . "'");

            if ($item['cat_oc_id'] > 0) {
                q("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
                q("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$item['cat_oc_id'] . "', main_category = '1'");
            }

            // Проверяем и создаём SEO URL, если его нет
            $seo_check = q("SELECT seo_url_id FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "' AND store_id = '" . STORE_ID . "'");
            if ($seo_check->num_rows == 0) {
                generate_seo_url($product_id, $item['name'], $main_lang_id);
            }

            $updated++;
        } else {
            // Вставка
            q("INSERT INTO " . DB_PREFIX . "product SET 
                model = '" . $model_esc . "',
                sku = '" . $sku_esc . "',
                upc = '" . $upc_esc . "',
                ean = '" . $ean_esc . "',
                jan = '" . $jan_esc . "',
                isbn = '" . $isbn_esc . "',
                image = '" . $image_esc . "',
                manufacturer_id = '" . (int)$item['man_id'] . "',
                shipping = '1',
                price = '" . (float)$base_price . "',
                quantity = '" . (int)$item['quantity'] . "',
                subtract = '" . (int)$item['subtract'] . "',
                weight = '" . (float)$item['weight'] . "',
                weight_class_id = '" . WEIGHT_CLASS_ID . "',
                length = '" . (float)$item['length'] . "',
                width = '" . (float)$item['width'] . "',
                height = '" . (float)$item['height'] . "',
                length_class_id = '" . LENGTH_CLASS_ID . "',
                status = '" . (int)$item['status'] . "',
                tax_class_id = '" . TAX_CLASS_ID . "',
                stock_status_id = '" . STOCK_STATUS_ID . "',
                date_available = '" . esc($item['date_available']) . "',
                date_added = NOW(),
                date_modified = NOW()");
            $product_id = db()->insert_id;

            foreach ($languages as $lang) {
                q("INSERT INTO " . DB_PREFIX . "product_description SET 
                    product_id = '" . (int)$product_id . "',
                    language_id = '" . (int)$lang['language_id'] . "',
                    name = '" . $name_esc . "',
                    description = '" . $desc_esc . "',
                    meta_title = '" . $name_esc . "',
                    meta_h1 = '" . $name_esc . "',
                    meta_description = '',
                    meta_keyword = ''");
            }

            if ($item['cat_oc_id'] > 0) {
                q("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$item['cat_oc_id'] . "', main_category = '1'");
            }

            q("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . STORE_ID . "'");
            q("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . STORE_ID . "', layout_id = '0'");

            // Генерируем SEO URL для нового товара
            generate_seo_url($product_id, $item['name'], $main_lang_id);

            $inserted++;
        }

        // Изображения
        if (!empty($item['images'])) {
            q("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
            $sort = 0;
            foreach ($item['images'] as $img) {
                $img_esc = esc($img);
                if ($sort > 0 || $img != $item['main_image']) {
                    q("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $img_esc . "', sort_order = '" . (int)$sort . "'");
                }
                $sort++;
            }
        }

        // Акционная цена (спеццена = текущая <price>, ниже базовой)
        if ($has_discount) {
            q("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
            q("INSERT INTO " . DB_PREFIX . "product_special SET 
                product_id = '" . (int)$product_id . "',
                customer_group_id = '" . CUSTOMER_GROUP_ID . "',
                priority = '1',
                price = '" . (float)$special_price . "',
                date_start = '0000-00-00',
                date_end = '0000-00-00'");
        }

        $existing_products[$model] = $product_id;
    }

    return ['inserted' => $inserted, 'updated' => $updated];
}

// Запуск
import_feed();