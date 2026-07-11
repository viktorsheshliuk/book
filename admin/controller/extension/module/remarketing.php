<?php

/*
 * WTFPL by CAPAXA https://ucrack.com
 */

class ControllerExtensionModuleRemarketing extends Controller
{
    private $error = [];
    private $version = "8.1";

    public function index()
    {
        $this->load->language("extension/module/remarketing");
        if (version_compare(VERSION, "3.0.0.0", ">=")) {
            $token = "user_token=" . $this->session->data["user_token"];
            $data["user_token"] = $this->session->data["user_token"];
            $extension = "marketplace/extension";
        } else {
            $token = "token=" . $this->session->data["token"];
            $data["token"] = $this->session->data["token"];
            $extension = "extension/extension";
        }
        $this->document->setTitle(strip_tags(html_entity_decode($this->language->get("heading_title"))));
        $this->load->model("setting/setting");
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->model_setting_setting->editSetting("remarketing", $this->request->post);
            if (version_compare(VERSION, "3.0.0.0", ">=")) {
                if ($this->request->post["remarketing_status"]) {
                    $this->request->post["module_remarketing_status"] = $this->request->post["remarketing_status"];
                }
                $this->model_setting_setting->editSetting("module_remarketing", $this->request->post);
            }
            $this->session->data["success"] = $this->language->get("text_success");
            $this->response->redirect($this->url->link($extension, $token . "&type=module", true));
        }
        $data["heading_title"] = $this->language->get("heading_title");
        $data["catalog_link"] = HTTPS_CATALOG;
        $language_data = $this->language->all();
        foreach ($language_data as $key => $value) {
            $data[$key] = $value;
        }
        if (isset($this->error["warning"])) {
            $data["error_warning"] = $this->error["warning"];
        } else {
            $data["error_warning"] = "";
        }
        $remarketingSettingKeys = [
            "remarketing_status",
            "remarketing_admin_status",
            "remarketing_autoclear_mode",
            "remarketing_bot_status",
            "remarketing_counter1",
            "remarketing_counter2",
            "remarketing_counter3",
            "remarketing_counter_bot",
            "remarketing_custom_begin_checkout_route",
            "remarketing_custom_checkout_route",
            "remarketing_debug_front_mode",
            "remarketing_debug_mode",
            "remarketing_ga4_currency",
            "remarketing_ga4_analytics_id",
            "remarketing_ga4_id",
            "remarketing_ga4_identifier",
            "remarketing_ga4_mp_api_secret",
            "remarketing_ga4_mp_status",
            "remarketing_ga4_resend_status",
            "remarketing_ga4_status",
            "remarketing_ga4_quick_order_event_name",
            "remarketing_ga4_dl_status",
            "remarketing_esputnik_api_status",
            "remarketing_esputnik_id",
            "remarketing_esputnik_currency",
            "remarketing_esputnik_login",
            "remarketing_esputnik_password",
            "remarketing_esputnik_status",
            "remarketing_esputnik_ttn_field",
            "remarketing_esputnik_webtracking_identifier",
            "remarketing_esputnik_webtracking_status",
            "remarketing_esputnik_external_id",
            "remarketing_events_cart",
            "remarketing_events_cart_add",
            "remarketing_events_purchase",
            "remarketing_events_quick_purchase",
            "remarketing_events_wishlist",
            "remarketing_facebook_currency",
            "remarketing_facebook_depth",
            "remarketing_facebook_id",
            "remarketing_facebook_identifier",
            "remarketing_facebook_lead",
            "remarketing_facebook_pixel_status",
            "remarketing_facebook_resend_status",
            "remarketing_facebook_script_status",
            "remarketing_facebook_server_side",
            "remarketing_facebook_status",
            "remarketing_facebook_test_code",
            "remarketing_facebook_token",
            "remarketing_feed_additional_images",
            "remarketing_feed_adult",
            "remarketing_feed_age_group",
            "remarketing_feed_all_attributes",
            "remarketing_feed_always_avail",
            "remarketing_feed_auto_min_price",
            "remarketing_feed_color",
            "remarketing_feed_currency",
            "remarketing_feed_currency_base",
            "remarketing_feed_custom_sql",
            "remarketing_feed_customer_group",
            "remarketing_feed_empty_brand",
            "remarketing_feed_export_options",
            "remarketing_feed_gender",
            "remarketing_feed_gtin",
            "remarketing_feed_highlight",
            "remarketing_feed_identifier",
            "remarketing_feed_key",
            "remarketing_feed_last_category",
            "remarketing_feed_material",
            "remarketing_feed_max_price",
            "remarketing_feed_min_price",
            "remarketing_feed_mpn",
            "remarketing_feed_ocstore_main",
            "remarketing_feed_option_color",
            "remarketing_feed_option_size",
            "remarketing_feed_original_description",
            "remarketing_feed_original_image_status",
            "remarketing_feed_replace_description",
            "remarketing_feed_replace_name",
            "remarketing_feed_rich_text",
            "remarketing_feed_short_desc",
            "remarketing_feed_size",
            "remarketing_feed_special",
            "remarketing_feed_status",
            "remarketing_feed_store_code",
            "remarketing_feed_tuning",
            "remarketing_feed_type_category",
            "remarketing_feed_utm",
            "remarketing_feed_utm_facebook",
            "remarketing_feed_utm_tiktok",
            "remarketing_feed_zero_quantity",
            "remarketing_ga4_dl_remove_prefix",
            "remarketing_ga4_dl_netpeak",
            "remarketing_ga4_language_id",
            "remarketing_ga4_only_purchase",
            "remarketing_ga4_seopro_categories",
            "remarketing_google_ads_identifier",
            "remarketing_google_ads_identifier_cart",
            "remarketing_google_ads_identifier_cart_page",
            "remarketing_google_ads_quick_order_identifier",
            "remarketing_google_currency",
            "remarketing_google_id",
            "remarketing_google_identifier",
            "remarketing_google_merchant_identifier",
            "remarketing_google_status",
            "remarketing_max_order_value",
            "remarketing_no_shipping",
            "remarketing_product_cost",
            "remarketing_reviews_feed_anonymous",
            "remarketing_reviews_feed_asin",
            "remarketing_reviews_feed_gtin",
            "remarketing_reviews_feed_mpn",
            "remarketing_reviews_feed_sku",
            "remarketing_reviews_feed_status",
            "remarketing_reviews_feed_key",
            "remarketing_reviews_quick_order_status",
            "remarketing_reviews_status",
            "remarketing_not_show_in_order",
            "remarketing_snapchat_currency",
            "remarketing_snapchat_id",
            "remarketing_snapchat_identifier",
            "remarketing_snapchat_pixel_status",
            "remarketing_snapchat_script_status",
            "remarketing_snapchat_status",
            "remarketing_telegram_bot_id",
            "remarketing_telegram_callback_oct",
            "remarketing_telegram_contact_form",
            "remarketing_telegram_review",
            "remarketing_telegram_send_to_id",
            "remarketing_telegram_status",
            "remarketing_tiktok_currency",
            "remarketing_tiktok_id",
            "remarketing_tiktok_identifier",
            "remarketing_tiktok_pixel_status",
            "remarketing_tiktok_resend_status",
            "remarketing_tiktok_script_status",
            "remarketing_tiktok_server_side",
            "remarketing_tiktok_status",
            "remarketing_tiktok_test_code",
            "remarketing_tiktok_token",
            "remarketing_uet_status"
        ];
        $remarketingDefaultSettings = [
            "remarketing_not_customer_groups" => [],
            "remarketing_ga4_refund_status" => [],
            "remarketing_ga4_send_status" => [],
            "remarketing_ga4_ratio" => 1,
            "remarketing_esputnik_address_format" => "{city} {address_1}",
            "remarketing_esputnik_cancelled_status" => [],
            "remarketing_esputnik_delivered_status" => [],
            "remarketing_esputnik_initialized_status" => [],
            "remarketing_esputnik_inprogress_status" => [],
            "remarketing_facebook_api_ver" => "22.0",
            "remarketing_facebook_depth_params" => "10,50,90",
            "remarketing_facebook_lead_send_status" => [],
            "remarketing_facebook_ratio" => 1,
            "remarketing_facebook_send_status" => [],
            "remarketing_feed_category" => [],
            "remarketing_feed_category_condition" => [],
            "remarketing_feed_category_custom_label_0" => [],
            "remarketing_feed_category_custom_label_1" => [],
            "remarketing_feed_category_custom_label_2" => [],
            "remarketing_feed_category_custom_label_3" => [],
            "remarketing_feed_category_custom_label_4" => [],
            "remarketing_feed_category_google_category" => [],
            "remarketing_feed_category_product_type" => [],
            "remarketing_feed_condition" => "new",
            "remarketing_feed_description" => "",
            "remarketing_feed_export_in_stock" => [],
            "remarketing_feed_export_out_of_stock" => [],
            "remarketing_feed_in_stock" => [],
            "remarketing_feed_manufacturer" => [],
            "remarketing_feed_multiplier" => 1,
            "remarketing_feed_out_of_stock" => [],
            "remarketing_feed_replace_from" => "",
            "remarketing_feed_replace_to" => "",
            "remarketing_reviews_country" => "UA",
            "remarketing_reviews_date" => "3",
            "remarketing_snapchat_ratio" => 1,
            "remarketing_tiktok_api_ver" => "1.3",
            "remarketing_tiktok_ratio" => 1,
            "remarketing_tiktok_send_status" => [],
            "remarketing_telegram_send_status" => [],
            "remarketing_telegram_message" => $this->language->get("text_default_tg_message"),
            "remarketing_google_ads_ratio" => 1
        ];
        foreach ($remarketingSettingKeys as $postKey) {
            if (isset($this->request->post[$postKey])) {
                $data[$postKey] = $this->request->post[$postKey];
            } else {
                $data[$postKey] = $this->config->get($postKey);
            }
        }
        foreach ($remarketingDefaultSettings as $configKey => $value) {
            if (isset($this->request->post[$configKey])) {
                $data[$configKey] = $this->request->post[$configKey];
            } elseif ($this->config->get($configKey)) {
                $data[$configKey] = $this->config->get($configKey);
            } else {
                $data[$configKey] = $value;
            }
        }
        $localisationModels = [
            "localisation/currency",
            "localisation/language",
            "localisation/order_status",
            "localisation/stock_status",
            "customer/customer_group",
            "catalog/category",
            "catalog/manufacturer"
        ];
        foreach ($localisationModels as $ocm) {
            $this->load->model($ocm);
        }
        if ($this->config->get("remarketing_ecommerce_currency")) {
            $data["remarketing_ga4_currency"] = $this->config->get("remarketing_ecommerce_currency");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_identifier")) {
            $data["remarketing_ga4_identifier"] = $this->config->get("remarketing_ecommerce_ga4_identifier");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_id")) {
            $data["remarketing_ga4_id"] = $this->config->get("remarketing_ecommerce_ga4_id");
        }
        if ($this->config->get("remarketing_ecommerce_ratio")) {
            $data["remarketing_ga4_ratio"] = $this->config->get("remarketing_ecommerce_ratio");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_status")) {
            $data["remarketing_ga4_status"] = $this->config->get("remarketing_ecommerce_ga4_status");
        }
        if ($this->config->get("remarketing_ecommerce_status")) {
            $data["remarketing_ga4_dl_status"] = $this->config->get("remarketing_ecommerce_status");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_measurement_status")) {
            $data["remarketing_ga4_mp_status"] = $this->config->get("remarketing_ecommerce_ga4_measurement_status");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_measurement_api_secret")) {
            $data["remarketing_ga4_mp_api_secret"] = $this->config->get(
                "remarketing_ecommerce_ga4_measurement_api_secret"
            );
        }
        if ($this->config->get("remarketing_ecommerce_ga4_analytics_id")) {
            $data["remarketing_ga4_analytics_id"] = $this->config->get("remarketing_ecommerce_ga4_analytics_id");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_send_status")) {
            $data["remarketing_ga4_send_status"] = $this->config->get("remarketing_ecommerce_ga4_send_status");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_resend_status")) {
            $data["remarketing_ga4_resend_status"] = $this->config->get("remarketing_ecommerce_ga4_resend_status");
        }
        if ($this->config->get("remarketing_ecommerce_ga4_refund_status")) {
            $data["remarketing_ga4_refund_status"] = $this->config->get("remarketing_ecommerce_ga4_refund_status");
        }
        $data["customer_groups"] = $this->model_customer_customer_group->getCustomerGroups();
        $data["currencies"] = $this->model_localisation_currency->getCurrencies();
        $languages = $this->model_localisation_language->getLanguages();
        $data["languages"] = [];
        $data["languages"][0] = $this->language->get("entry_ga4_language_id_first");
        foreach ($languages as $language) {
            if (!$language["status"]) {
            } else {
                $data["languages"][$language["language_id"]] = $language["name"];
            }
        }
        $data["order_statuses"] = $this->model_localisation_order_status->getOrderStatuses();
        if ($data["remarketing_feed_tuning"]) {
            $filter_data = ["sort" => "name", "order" => "ASC"];
            $data["categories"] = $this->model_catalog_category->getCategories($filter_data);
            $filter_data = ["sort" => "name", "order" => "ASC"];
            $data["manufacturers"] = $this->model_catalog_manufacturer->getManufacturers($filter_data);
        } else {
            $data["categories"] = [];
            $data["manufacturers"] = [];
        }
        $data["stock_statuses"] = $this->model_localisation_stock_status->getStockStatuses();
        $data["check_install"] = $this->checkInstall();
        $data["max_input_vars_warning"] = $this->config->get("remarketing_feed_tuning") && ini_get(
            "max_input_vars"
        ) < 5001 ? sprintf($this->language->get("text_vars_warning"), ini_get("max_input_vars")) : false;
        $data["jetcache_warning"] = false;
        if ($this->config->get("asc_jetcache_settings")) {
            $jetcache_settings = $this->config->get("asc_jetcache_settings");
            if ($jetcache_settings["jetcache_widget_status"] && $jetcache_settings["cont_status"]) {
                foreach ($jetcache_settings["add_cont"] as $cont) {
                    if ($cont["cont"] == "common/footer" && $cont["status"] == "1") {
                        $data["jetcache_warning"] = $this->language->get("text_jetcache_warning");
                    }
                }
            }
        }
        $data["seopro_warning"] = defined("VERSION_CORE") && VERSION_CORE == "ocStore" && version_compare(
            VERSION,
            "3.0.0.0",
            ">="
        ) && $this->config->get("config_seo_pro") && !$this->config->get(
            "config_valide_param_flag"
        ) ? $this->language->get("text_seopro_warning") : false;
        $data["theme_editor_warning"] = version_compare(VERSION, "3.0.0.0", ">=") && 0 < $this->db->query(
            "SELECT * FROM `" . DB_PREFIX . "theme` WHERE `route` LIKE '%common%' OR `route` LIKE '%product%'"
        )->num_rows ? $this->language->get("text_theme_editor_warning") : false;
        $data["settings_warning"] = [];
        if (!$this->config->get("remarketing_status")) {
            $data["settings_warning"][] = $this->language->get("text_status_warning");
        }
        if ($this->config->get("remarketing_google_status") && !$this->config->get("remarketing_google_identifier")) {
            $data["settings_warning"][] = $this->language->get("text_ads_warning");
        }
        if ($this->config->get("remarketing_reviews_status") && (!$this->config->get(
                    "remarketing_google_merchant_identifier"
                ) || !$this->config->get("remarketing_reviews_country") || !$this->config->get(
                    "remarketing_reviews_date"
                ))) {
            $data["settings_warning"][] = $this->language->get("text_reviews_warning");
        }
        if ($this->config->get("remarketing_ga4_status") && !$this->config->get("remarketing_ga4_identifier")) {
            $data["settings_warning"][] = $this->language->get("text_gtag_warning");
        }
        if ($this->config->get("remarketing_esputnik_webtracking_status") && !$this->config->get(
                "remarketing_esputnik_webtracking_identifier"
            )) {
            $data["settings_warning"][] = $this->language->get("text_esputnik_warning");
        }
        if ($this->config->get("remarketing_feed_status") && !$this->config->get("remarketing_feed_key")) {
            $data["settings_warning"][] = $this->language->get("text_feed_key_warning");
        }
        if ($this->config->get("remarketing_reviews_status") && !$this->config->get("remarketing_reviews_feed_key")) {
            $data["settings_warning"][] = $this->language->get("text_rfeed_key_warning");
        }
        if ($this->config->get("remarketing_ga4_mp_status")) {
            if (!$this->config->get("remarketing_ga4_mp_api_secret") || !$this->config->get(
                    "remarketing_ga4_analytics_id"
                )) {
                $data["settings_warning"][] = $this->language->get("text_mp_id_warning");
            }
            if (!$this->config->get("remarketing_ga4_send_status")) {
                $data["settings_warning"][] = $this->language->get("text_mp_status_warning");
            }
        }
        if ($this->config->get("remarketing_facebook_server_side")) {
            if (!$this->config->get("remarketing_facebook_token") || !$this->config->get(
                    "remarketing_facebook_identifier"
                )) {
                $data["settings_warning"][] = $this->language->get("text_fb_id_warning");
            }
            if (!$this->config->get("remarketing_facebook_send_status")) {
                $data["settings_warning"][] = $this->language->get("text_fb_status_warning");
            }
        }
        if (!empty($data["check_install"])) {
            $data["settings_warning"][] = $this->language->get("text_install_warning") . $data["check_install"];
        }
        $data["taxonomy_warning"] = !$this->db->query(
            "SELECT * FROM `" . DB_PREFIX . "remarketing_taxonomy_ru`"
        )->num_rows ? $this->language->get("text_taxonomy_warning") : "";
        $log_path = DIR_LOGS;
        $log_files = glob($log_path . "remarketing_log_*.log");
        usort($log_files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $data["logs"] = [];
        $data["logs"][] = [
            "name" => "DB - last 1 day",
            "view" => $this->url->link("extension/module/remarketing/view_log", $token . "&log=db", true)
        ];
        foreach ($log_files as $log) {
            $log_name = basename($log);
            if (strpos($log_name, "ga4") || strpos($log_name, "facebook")) {
                $log_size = $this->formatSize(filesize($log));
                $data["logs"][] = [
                    "name" => $log_name . " (" . $log_size . ")",
                    "view" => $this->url->link(
                        "extension/module/remarketing/view_log",
                        $token . "&log=" . urlencode($log_name),
                        true
                    )
                ];
            }
        }
        $data["db_info"] = $this->getDbInfo();
        $data["breadcrumbs"] = [
            [
                "text" => $this->language->get("text_home"),
                "href" => $this->url->link("common/dashboard", $token, true)
            ],
            [
                "text" => $this->language->get("text_extension"),
                "href" => $this->url->link($extension, $token . "&type=module", true)
            ],
            [
                "text" => $this->language->get("heading_title"),
                "href" => $this->url->link("extension/module/remarketing", $token, true)
            ]
        ];
        $data["action"] = $this->url->link("extension/module/remarketing", $token, true);
        $data["cancel"] = $this->url->link($extension, $token . "&type=module", true);
        $data["link_merchant"] = HTTPS_CATALOG . "index.php?route=extension/feed/remarketing_feed";
        $data["link_facebook"] = HTTPS_CATALOG . "index.php?route=extension/feed/remarketing_feed&target=facebook";
        $data["link_tiktok"] = HTTPS_CATALOG . "index.php?route=extension/feed/remarketing_feed&target=tiktok";
        $data["link_esputnik"] = HTTPS_CATALOG . "index.php?route=extension/feed/remarketing_feed&target=esputnik";
        $data["link_ads"] = HTTPS_CATALOG . "index.php?route=extension/feed/remarketing_feed/generateCsvFeed";
        $data["test_facebook"] = HTTPS_CATALOG . "index.php?route=common/remarketing/sendFbCapi";
        $data["test_tg"] = HTTPS_CATALOG . "index.php?route=common/remarketing/sendTg";
        $data["load_taxonomy"] = html_entity_decode(
            $this->url->link("extension/module/remarketing/loadTaxonomy", $token, true)
        );
        $data["save_ajax"] = html_entity_decode(
            $this->url->link("extension/module/remarketing/saveAjax", $token, true)
        );
        $data["try_fill"] = html_entity_decode($this->url->link("extension/module/remarketing/tryFill", $token, true));
        $data["db_clear"] = html_entity_decode($this->url->link("extension/module/remarketing/dbClear", $token, true));
        $data["logs_clear"] = html_entity_decode(
            $this->url->link("extension/module/remarketing/logsClear", $token, true)
        );
        $data["search_in_files"] = html_entity_decode(
            $this->url->link("extension/module/remarketing/searchInFiles", $token, true)
        );
        $data["remarketing_report_link"] = html_entity_decode(
            $this->url->link("report/remarketing_report", $token, true)
        );
        $data["version"] = $this->version;
        $data["domain"] = HTTPS_CATALOG;
        $data["header"] = $this->load->controller("common/header");
        $data["column_left"] = $this->load->controller("common/column_left");
        $data["footer"] = $this->load->controller("common/footer");
        $this->response->setOutput($this->load->view("extension/module/remarketing", $data));
    }

    private function formatSize($size)
    {
        $units = ["B", "KB", "MB", "GB", "TB"];
        for ($unit_index = 0; 1024 <= $size && $unit_index < count($units) - 1; $unit_index++) {
            $size .= 1024;
        }
        return round($size, 2) . " " . $units[$unit_index];
    }

    public function getDbInfo()
    {
        $query = $this->db->query(
            "SELECT ROUND((data_length + index_length) / 1024 / 1024, 2) AS db_size, table_rows AS count_rows FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '" . DB_PREFIX . "remarketing_orders'"
        );
        if ($query->num_rows) {
            return ["db_size" => $query->row["db_size"], "count_rows" => $query->row["count_rows"]];
        }
    }

    public function view_log()
    {
        $this->load->language("extension/module/remarketing");
        $log_name = !empty($this->request->get["log"]) ? $this->request->get["log"] : "";
        $file_path = DIR_LOGS . $log_name;
        $decoded_logs = [];
        if ($log_name == "db") {
            $log_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "remarketing_log ORDER BY date_added DESC");
            foreach ($log_query->rows as $row) {
                $event_data = json_decode($row["event"], true);
                if (!empty($event_data["data"][0])) {
                    $event_data["data"][0] = json_decode($event_data["data"][0], true);
                    $event_name = "fb - " . $event_data["data"][0]["event_name"];
                }
                if (!empty($event_data["events"][0]["name"])) {
                    $event_name = "ga4 - " . $event_data["events"][0]["name"];
                }
                if (!empty($event_name)) {
                    $decoded_logs[] = [
                        "date" => $row["date_added"],
                        "timestamp" => strtotime($row["date_added"]),
                        "event_name" => $event_name,
                        "event_data" => json_encode($event_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    ];
                }
            }
        } else {
            $content = file_get_contents($file_path);
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $line) {
                if (preg_match("/^(?<date>[\\d\\-]+\\s[\\d\\:]+)\\s\\-\\s(?<json>{.*})\$/", $line, $matches)) {
                    $date = $matches["date"];
                    $json_data = json_decode($matches["json"], true);
                    if ($json_data) {
                        switch (1) {
                            case !empty($json_data["data"]):
                                if (!empty($json_data["data"][0])) {
                                    $event_data = json_decode($json_data["data"][0], true);
                                } else {
                                    $event_data = json_decode("{}", true);
                                }
                                $decoded_logs[] = [
                                    "date" => $date,
                                    "timestamp" => strtotime($date),
                                    "event_name" => !empty($event_data["event_name"]) ? $event_data["event_name"] : "N/A",
                                    "event_data" => json_encode($event_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                ];
                                break;
                            case !empty($json_data["events"]):
                                foreach ($json_data["events"] as $event) {
                                    $decoded_logs[] = [
                                        "date" => $date,
                                        "timestamp" => strtotime($date),
                                        "event_name" => !empty($event_data["name"]) ? $event_data["name"] : "N/A",
                                        "event_data" => json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                    ];
                                }
                                break;
                        }
                    }
                }
            }
        }
        usort($decoded_logs, function ($a, $b) {
            if ($a["timestamp"] == $b["timestamp"]) {
                return 0;
            }
            return $a["timestamp"] < $b["timestamp"] ? 1 : -1;
        });
        $data["decoded_logs"] = $decoded_logs;
        $data["heading_title"] = $log_name;
        $this->document->setTitle("View Log - " . strip_tags(html_entity_decode($log_name)));
        $data["header"] = $this->load->controller("common/header");
        $data["column_left"] = $this->load->controller("common/column_left");
        $data["footer"] = $this->load->controller("common/footer");
        $this->response->setOutput($this->load->view("extension/module/remarketing_log", $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission("modify", "extension/module/remarketing")) {
            $this->error["warning"] = $this->language->get("error_permission");
        }
        return !$this->error;
    }

    protected function checkInstall()
    {
        $check = "";
        if (version_compare(VERSION, "3.0.0.0", ">=")) {
            $files = [
                "admin/controller/sale/order.php",
                "catalog/controller/common/header.php",
                "catalog/controller/common/footer.php",
                "catalog/controller/product/product.php",
                "catalog/controller/product/category.php",
                "catalog/controller/product/manufacturer.php",
                "catalog/controller/product/search.php",
                "catalog/controller/product/special.php",
                "catalog/controller/checkout/cart.php",
                "catalog/controller/account/wishlist.php",
                "catalog/model/checkout/order.php",
                "catalog/model/account/customer.php",
                "admin/view/template/sale/order_info.twig",
                "catalog/view/theme/{*}/template/common/header.twig",
                "catalog/view/theme/{*}/template/common/footer.twig",
                "catalog/view/theme/{*}/template/product/product.twig",
                "catalog/view/theme/{*}/template/product/category.twig",
                "catalog/view/theme/{*}/template/product/manufacturer_info.twig",
                "catalog/view/theme/{*}/template/product/search.twig",
                "catalog/view/theme/{*}/template/product/special.twig"
            ];
        } else {
            $files = [
                "admin/controller/sale/order.php",
                "catalog/controller/common/header.php",
                "catalog/controller/common/footer.php",
                "catalog/controller/product/product.php",
                "catalog/controller/product/category.php",
                "catalog/controller/product/manufacturer.php",
                "catalog/controller/product/search.php",
                "catalog/controller/product/special.php",
                "catalog/controller/checkout/cart.php",
                "catalog/controller/account/wishlist.php",
                "catalog/model/checkout/order.php",
                "catalog/model/account/customer.php",
                "admin/view/template/sale/order_info.tpl",
                "catalog/view/theme/{*}/template/common/header.tpl",
                "catalog/view/theme/{*}/template/common/footer.tpl",
                "catalog/view/theme/{*}/template/product/product.tpl",
                "catalog/view/theme/{*}/template/product/category.tpl",
                "catalog/view/theme/{*}/template/product/manufacturer_info.tpl",
                "catalog/view/theme/{*}/template/product/search.tpl",
                "catalog/view/theme/{*}/template/product/special.tpl"
            ];
        }
        if ($this->config->get("config_theme") == "theme_default") {
            $theme = $this->config->get("theme_default_directory");
        } else {
            $theme = $this->config->get("config_theme");
        }
        foreach ($files as $file) {
            $file = str_replace("{*}", $theme, $file);
            $filename = DIR_MODIFICATION . $file;
            if (file_exists($filename) && strpos(file_get_contents($filename), "// remarketing")) {
            } else {
                $check .= $file . " - " . "<i class=\"fa fa-times\" style=\"color:red\"></i><br>";
            }
        }
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "remarketing_log` (`source` VARCHAR(64) NOT NULL , `event` TEXT NOT NULL , `date_added` DATETIME NOT NULL) ENGINE = MyISAM CHARSET=utf8 COLLATE utf8_general_ci;"
        );
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "remarketing_taxonomy_en` (`category_id` int(11) NOT NULL,`lvl1` varchar(512) NOT NULL,`lvl2` varchar(512) NOT NULL,`lvl3` varchar(512) NOT NULL,`lvl4` varchar(512) NOT NULL,`lvl5` varchar(512) NOT NULL,`lvl6` varchar(512) NOT NULL,`lvl7` varchar(512) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
        );
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "remarketing_taxonomy_ru` (`category_id` int(11) NOT NULL,`lvl1` varchar(512) NOT NULL,`lvl2` varchar(512) NOT NULL,`lvl3` varchar(512) NOT NULL,`lvl4` varchar(512) NOT NULL,`lvl5` varchar(512) NOT NULL,`lvl6` varchar(512) NOT NULL,`lvl7` varchar(512) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
        );
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "remarketing_orders` (`order_id` int(11) NOT NULL,`ga4` datetime NOT NULL,`facebook` datetime NOT NULL,`esputnik` datetime NOT NULL,`telegram` datetime NOT NULL,`success_page` datetime NOT NULL,`date_added` datetime NOT NULL, PRIMARY KEY (`order_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
        );
        $query = $this->db->query("DESC `" . DB_PREFIX . "remarketing_orders`");
        $fields = [];
        foreach ($query->rows as $row) {
            $fields[] = $row["Field"];
        }
        if (!in_array("facebook_lead", $fields)) {
            $this->db->query(
                "ALTER TABLE `" . DB_PREFIX . "remarketing_orders` ADD `facebook_lead` datetime NOT NULL AFTER `telegram`"
            );
        }
        if (!in_array("tiktok", $fields)) {
            $this->db->query(
                "ALTER TABLE `" . DB_PREFIX . "remarketing_orders` ADD `tiktok` datetime NOT NULL AFTER `telegram`"
            );
        }
        if (!in_array("ga4", $fields)) {
            $this->db->query(
                "ALTER TABLE `" . DB_PREFIX . "remarketing_orders` ADD `ga4` datetime NOT NULL AFTER `telegram`"
            );
            $this->db->query("UPDATE `" . DB_PREFIX . "remarketing_orders` SET `ga4` = `ecommerce_ga4`");
            $this->db->query("UPDATE `" . DB_PREFIX . "remarketing_orders` SET `order_data` = ''");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "remarketing_orders` DROP COLUMN `order_data`");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "remarketing_orders` DROP COLUMN `ecommerce`");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "remarketing_orders` DROP COLUMN `ecommerce_ga4`");
        }
        if (!in_array("quick_order", $fields)) {
            $this->db->query(
                "ALTER TABLE `" . DB_PREFIX . "remarketing_orders` ADD `quick_order` tinyint(1) NOT NULL AFTER `telegram`"
            );
        }
        $parameters = [
            "uuid",
            "ga4_uuid",
            "fbclid",
            "fbc",
            "fbp",
            "gclid",
            "dclid",
            "utm_source",
            "utm_campaign",
            "utm_medium",
            "utm_term",
            "utm_content",
            "utm_referrer",
            "ttclid",
            "fb_event_id",
            "fb_lead_event_id",
            "tt_event_id",
            "first_referrer",
            "last_referrer"
        ];
        $fields_query = $this->db->query("DESC `" . DB_PREFIX . "remarketing_orders`");
        $fields = [];
        foreach ($fields_query->rows as $row) {
            $fields[] = $row["Field"];
        }
        foreach ($parameters as $parameter) {
            if (!in_array($parameter, $fields)) {
                $this->db->query(
                    "ALTER TABLE `" . DB_PREFIX . "remarketing_orders` ADD `" . $parameter . "` VARCHAR(255) NOT NULL AFTER `date_added`"
                );
            }
        }
        return $check;
    }

    public function saveAjax()
    {
        $json = [];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            uasort($this->request->post, function () {
                return rand() - rand();
            });
            $this->load->model("setting/setting");
            $this->load->language("extension/module/remarketing");
            $this->model_setting_setting->editSetting("remarketing", $this->request->post);
            if (version_compare(VERSION, "3.0.0.0", ">=")) {
                if ($this->request->post["remarketing_status"]) {
                    $this->request->post["module_remarketing_status"] = $this->request->post["remarketing_status"];
                }
                $this->model_setting_setting->editSetting("module_remarketing", $this->request->post);
            }
            $json["success"] = $this->language->get("text_ajax_success");
        }
        $this->response->addHeader("Content-Type: application/json; charset=utf-8");
        $this->response->setOutput(json_encode($json));
    }

    public function loadTaxonomy()
    {
        $response = file_get_contents(DIR_APPLICATION . 'model/report/taxonomy.sql');
        $response = strip_tags($response);
        if ($response) {
            $response = str_replace("oc_remarketing_taxonomy", DB_PREFIX . "remarketing_taxonomy", $response);
            $queries = array_filter(array_map("trim", explode(";", $response)));
            foreach ($queries as $query) {
                $this->db->query($query);
            }
            echo "ok";
        }
    }

    public function tryFill()
    {
        $json = [];
        $json["categories"] = [];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $language = $this->config->get("config_language");
            $languageCode = preg_match(
                "/\\b(ru(-ru)?|uk(-ua)?|kk(-kk)?|be(-by)?|uz(-uz)?|ky(-kg)?|tg(-tj)?|tk(-tm)?|az(-az)?|hy(-am)?|mo(-md)?|mn(-mn)?|ka(-ge)?)\\b/i",
                $language
            ) ? "ru" : "en";
            $load_taxonomy = $this->db->query(
                "SELECT category_id, CONCAT_WS(' > ', NULLIF(lvl1, ''), NULLIF(lvl2, ''), NULLIF(lvl3, ''), NULLIF(lvl4, ''), NULLIF(lvl5, ''), NULLIF(lvl6, ''), NULLIF(lvl7, '')) AS full_path FROM " . DB_PREFIX . "remarketing_taxonomy_" . $languageCode
            );
            $load_categories = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_description`");
            foreach ($load_categories->rows as $category) {
                foreach ($load_taxonomy->rows as $taxonomy) {
                    if (preg_match("/> " . preg_quote($category["name"], "/") . "\$/i", $taxonomy["full_path"])) {
                        $json["categories"][$category["category_id"]] = [
                            "id" => $taxonomy["category_id"],
                            "full_path" => $taxonomy["full_path"]
                        ];
                    }
                }
            }
            $json["success"] = "true";
        }
        $this->response->addHeader("Content-Type: application/json; charset=utf-8");
        $this->response->setOutput(json_encode($json));
    }

    public function dbClear()
    {
        $json = [];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "remarketing_orders`");
            $json["success"] = "true";
        }
        $this->response->addHeader("Content-Type: application/json; charset=utf-8");
        $this->response->setOutput(json_encode($json));
    }

    public function logsClear()
    {
        $json = [];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(DIR_LOGS));
            foreach ($files as $file) {
                if ($file->isFile() && strpos(basename($file->getPathname()), "remarketing_log") !== false) {
                    @unlink(@$file->getPathname());
                }
            }
            $json["success"] = "true";
        }
        $this->response->addHeader("Content-Type: application/json; charset=utf-8");
        $this->response->setOutput(json_encode($json));
    }

    public function searchInFiles()
    {
        $json = [];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $directories = [DIR_CATALOG, DIR_MODIFICATION];
            $patterns = ["gtag(", "dataLayer", "fbq("];
            $found_files = [];
            $json["success"] = true;
            foreach ($directories as $directory) {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
                foreach ($files as $file) {
                    if ($file->isFile() && $file->getExtension()[[
                            "php" => true,
                            "tpl" => true,
                            "twig" => true,
                            "js" => true
                        ]]) {
                        $file_path = $file->getPathname();
                        if (strpos(basename($file_path), "remarketing") !== false) {
                        } else {
                            $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            $matches = [];
                            foreach ($lines as $line_num => $line) {
                                foreach ($patterns as $pattern) {
                                    if (strpos($line, $pattern) !== false) {
                                        $matches[$line_num + 1] = trim($line);
                                    }
                                }
                            }
                            if (!empty($matches)) {
                                $found_files[$file_path] = $matches;
                            }
                        }
                    }
                }
            }
            ksort($found_files);
            if (!empty($found_files)) {
                $html = "<div class=\"search-results\">";
                foreach ($found_files as $file => $lines) {
                    $html .= "<div class=\"file-result\">";
                    $html .= "<strong>" . htmlspecialchars($file, ENT_QUOTES, "UTF-8") . "</strong><br>";
                    $html .= "<ul>";
                    foreach ($lines as $line_num => $text) {
                        $html .= "<li><strong>" . $line_num . ":</strong> " . htmlspecialchars(
                                $text,
                                ENT_QUOTES,
                                "UTF-8"
                            ) . "</li>";
                    }
                    $html .= "</ul>";
                    $html .= "</div>";
                }
                $html .= "</div>";
                $json["html"] = $html;
            } else {
                $json["html"] = "<i class=\"fa fa-check\"></i> NOT FOUND";
            }
        }
        $this->response->addHeader("Content-Type: application/json; charset=utf-8");
        $this->response->setOutput(json_encode($json));
    }

    public function gcautocomplete()
    {
        $json = [];
        if (isset($this->request->get["filter_name"])) {
            $language = $this->config->get("config_language");
            $languageCode = preg_match(
                "/\\b(ru(-ru)?|uk(-ua)?|kk(-kk)?|be(-by)?|uz(-uz)?|ky(-kg)?|tg(-tj)?|tk(-tm)?|az(-az)?|hy(-am)?|mo(-md)?|mn(-mn)?|ka(-ge)?)\\b/i",
                $language
            ) ? "ru" : "en";
            $load_taxonomy = $this->db->query(
                "SELECT category_id, CONCAT_WS(' > ', NULLIF(lvl1, ''), NULLIF(lvl2, ''), NULLIF(lvl3, ''), NULLIF(lvl4, ''), NULLIF(lvl5, ''), NULLIF(lvl6, ''), NULLIF(lvl7, '')) AS full_path FROM " . DB_PREFIX . "remarketing_taxonomy_" . $languageCode . " WHERE CONCAT_WS(' > ', NULLIF(lvl1, ''), NULLIF(lvl2, ''), NULLIF(lvl3, ''), NULLIF(lvl4, ''), NULLIF(lvl5, ''), NULLIF(lvl6, ''), NULLIF(lvl7, '')) LIKE '%" . $this->db->escape(
                    $this->request->get["filter_name"]
                ) . "%' LIMIT 10"
            );
            foreach ($load_taxonomy->rows as $row) {
                $json[] = ["full_path" => $row["full_path"]];
            }
        }
        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function install()
    {
        $this->load->model("user/user_group");
        $this->model_user_user_group->addPermission($this->user->getGroupId(), "access", "report/remarketing_report");
        $this->checkInstall();
    }

    public function uninstall()
    {
        if ($this->user->hasPermission("modify", "extension/module/remarketing")) {
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "remarketing_orders`");
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "remarketing_log`");
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "remarketing_taxonomy_ru`");
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "remarketing_taxonomy_en`");
            $this->load->model("setting/setting");
            $this->model_setting_setting->deleteSetting("remarketing");
            $this->model_setting_setting->deleteSetting("module_remarketing");
        }
    }
}