<?php
/**
 * ПРИМЕР конфигурации скрипта импорта фида
 *
 * Скопируйте этот файл в config.php и укажите свои настройки.
 * config.php добавлен в .gitignore и не попадает в репозиторий.
 *
 * Запуск по крону: php /path/to/price/import/import.php
 */

// URL фида (можно заменить на http://...)
//define('FEED_SOURCE', __DIR__ . '/feed_sample.xml');
// Если фид скачивается по URL, указать:
define('FEED_SOURCE', 'https://example.com/index.php?route=extension/feed/unixml/yandex');
define('FEED_CACHE_FILE', __DIR__ . '/feed_cached.xml');
define('FEED_CACHE_TTL', 3600); // 1 час
define('BATCH_SIZE', 50);
define('LOG_FILE', __DIR__ . '/import.log');
define('CATEGORY_STATUS', 1);
define('STORE_ID', 0);
define('LENGTH_CLASS_ID', 1);
define('WEIGHT_CLASS_ID', 1);
define('TAX_CLASS_ID', 0);
define('STOCK_STATUS_ID', 7);
define('CUSTOMER_GROUP_ID', 1);

// DB Config
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'book');
define('DB_PORT', 3306);
define('DB_PREFIX', 'oc_');
define('DB_CHARSET', 'utf8');

define('IMAGE_DIR', __DIR__ . '/../../image/');