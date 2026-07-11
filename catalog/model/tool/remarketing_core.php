<?php

/*
 * WTFPL by CAPAXA https://ucrack.com
 */

class ModelToolRemarketingCore extends Model
{
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->ads_id = $this->config->get("remarketing_google_id");
        $this->ga4_id = $this->config->get("remarketing_ga4_id");
        $this->fb_id = $this->config->get("remarketing_facebook_id");
        $this->snapchat_id = $this->config->get("remarketing_snapchat_id");
        $this->tiktok_id = $this->config->get("remarketing_tiktok_id");
        $this->esputnik_id = $this->config->get("remarketing_esputnik_id");
        $this->ads_currency = $this->config->get("remarketing_google_currency");
        $this->ga4_currency = $this->config->get("remarketing_ga4_currency");
        $this->fb_currency = $this->config->get("remarketing_facebook_currency");
        $this->snapchat_currency = $this->config->get("remarketing_snapchat_currency");
        $this->tiktok_currency = $this->config->get("remarketing_tiktok_currency");
        $this->esputnik_currency = $this->config->get("remarketing_esputnik_currency");
    }

    public function getOrderRemarketing($order_id, $modification = [])
    {
        $this->load->model("catalog/product");
        $this->load->model("checkout/order");
        $this->load->model("tool/remarketing");
        if (!empty($this->session->data["quick_order"])) {
            $this->db->query(
                "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `quick_order` = 1 WHERE order_id = '" . (int)$order_id . "'"
            );
        }
        $order_query = $this->db->query(
            "SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'"
        );
        if ($order_query->num_rows) {
            $db_order = $order_query->row;
            $products = [];
            $ads_products = [];
            $ga4_products = [];
            $fb_contents = [];
            $fb_content_ids = [];
            $tiktok_contents = [];
            $tiktok_content_ids = [];
            $snapchat_item_ids = [];
            $uet_product_ids = [];
            $uet_items = [];
            $esputnik_purchased_items = [];
            $client_data = [];
            $reviews_event = false;
            $ads_event = false;
            $ads_conversion = false;
            $ga4_datalayer = false;
            $ga4_event = false;
            $ga4_event_name = "purchase";
            $fb_event = false;
            $fb_capi_event = false;
            $fb_lead_event = false;
            $tiktok_event = false;
            $tiktok_api_event = false;
            $snapchat_event = false;
            $uet_event = false;
            $esputnik_event = false;
            $esputnik_customer_data_extra = false;
            $ads_price_ratio = (double)$this->config->get("remarketing_google_ads_ratio");
            $ga4_price_ratio = (double)$this->config->get("remarketing_ga4_ratio");
            $fb_price_ratio = (double)$this->config->get("remarketing_facebook_ratio");
            $tiktok_price_ratio = (double)$this->config->get("remarketing_tiktok_ratio");
            $snapchat_price_ratio = (double)$this->config->get("remarketing_snapchat_ratio");
            $cost_field = $this->config->get("remarketing_product_cost");
            $esputnik_currency = $this->config->get("remarketing_esputnik_currency");
            $order_product_query = $this->db->query(
                "SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"
            );
            $i = 1;
            $cost = 0;

            foreach ($order_product_query->rows as $product) {
                $option_data = "";
                $order_option_query = $this->db->query(
                    "SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product["order_product_id"] . "'"
                );
                foreach ($order_option_query->rows as $option) {
                    if ($option["type"] != "file") {
                        $option_data .= $option["name"] . ":" . $option["value"] . ";";
                    }
                }
                $option_data = rtrim($option_data, ";");
                if ($option_data) {
                    $variant = str_replace("\n", " ", $option_data);
                } else {
                    $variant = "";
                }
                $product_info = $this->model_catalog_product->getProduct($product["product_id"]);
                if (!empty($cost_field) && !empty($product_info[$cost_field])) {
                    (double)$product_info[$cost_field];
                    $product >>= "price";
                    $cost += (double)$product_info[$cost_field] * $product["quantity"];
                }
                $ga4_categories = $this->model_tool_remarketing->getRemarketingCategoriesGa4($product["product_id"]);
                if (4 < count($order_product_query->rows)) {
                    $ga4_categories = [];
                }
                $product["category"] = $this->model_tool_remarketing->getRemarketingCategories($product["product_id"]);
                $products[] = [
                    "name" => $product["name"],
                    "product_id" => $product["product_id"],
                    "product_info" => $product_info,
                    "model" => $product["model"],
                    "category" => $product["category"],
                    "variant" => $variant,
                    "price" => $this->currency->format($product["price"], $this->session->data["currency"], "", false),
                    "google_price" => $this->currency->format(
                        $product["price"] * $ads_price_ratio,
                        $this->ads_currency,
                        "",
                        false
                    ),
                    "facebook_price" => $this->currency->format(
                        $product["price"] * $fb_price_ratio,
                        $this->fb_currency,
                        "",
                        false
                    ),
                    "tiktok_price" => $this->currency->format(
                        $product["price"] * $tiktok_price_ratio,
                        $this->tiktok_currency,
                        "",
                        false
                    ),
                    "ga4_price" => $this->currency->format(
                        $product["price"] * $ga4_price_ratio,
                        $this->ga4_currency,
                        "",
                        false
                    ),
                    "esputnik_price" => $this->currency->format($product["price"], $this->esputnik_currency, "", false),
                    "quantity" => $product["quantity"],
                    "total" => $this->currency->format(
                        $product["price"] * $product["quantity"],
                        $this->session->data["currency"]
                    )
                ];
                $ga4_product_item = [
                    "item_id" => (string)$product_info[$this->ga4_id],
                    "id" => (string)$product_info[$this->ga4_id],
                    "google_business_vertical" => "retail",
                    "item_name" => $this->model_tool_remarketing->getGa4Name($product["product_id"], $product["name"]),
                    "item_brand" => !empty($product_info["manufacturer"]) ? $product_info["manufacturer"] : "",
                    "item_variant" => $variant,
                    "index" => (int)$i,
                    "quantity" => (int)$product["quantity"],
                    "price" => (double)$this->currency->format(
                        $product["price"] * $ga4_price_ratio,
                        $this->ga4_currency,
                        "",
                        false
                    )
                ];
                if (!empty($ga4_categories[0])) {
                    $ga4_product_item["item_category"] = $ga4_categories[0];
                }
                if (!empty($ga4_categories[1])) {
                    $ga4_product_item["item_category2"] = $ga4_categories[1];
                }
                if (!empty($ga4_categories[2])) {
                    $ga4_product_item["item_category3"] = $ga4_categories[2];
                }
                if (!empty($ga4_categories[3])) {
                    $ga4_product_item["item_category4"] = $ga4_categories[3];
                }
                $ga4_products[] = $ga4_product_item;
                $ads_products[] = [
                    "id" => $product[$this->ads_id],
                    "price" => $this->currency->format(
                        $product["price"] * $ads_price_ratio,
                        $this->ads_currency,
                        "",
                        false
                    ),
                    "quantity" => $product["quantity"],
                    "google_business_vertical" => "retail"
                ];
                $fb_contents[] = [
                    "id" => $product[$this->fb_id],
                    "item_price" => $this->currency->format($product["price"], $this->fb_currency, "", false),
                    "quantity" => $product["quantity"]
                ];
                $fb_content_ids[] = $product[$this->fb_id];
                $tiktok_contents[] = [
                    "content_id" => $product[$this->tiktok_id],
                    "price" => $this->currency->format($product["price"], $this->tiktok_currency, "", false),
                    "content_name" => $product["name"],
                    "content_category" => $product["category"],
                    "quantity" => $product["quantity"]
                ];
                $tiktok_content_ids[] = $product[$this->tiktok_id];
                $uet_items[] = [
                    "id" => $product["product_id"],
                    "quantity" => $product["quantity"],
                    "price" => $this->currency->format($product["price"], $this->ga4_currency, "", false)
                ];
                $esputnik_purchased_items[] = [
                    "productKey" => (string)$product[$this->esputnik_id],
                    "price" => (string)$this->currency->format($product["price"], $this->esputnik_currency, "", false),
                    "quantity" => (string)$product["quantity"],
                    "currency" => $this->esputnik_currency
                ];
                $snapchat_item_ids[] = $product[$this->snapchat_id];
                $uet_product_ids[] = $product["product_id"];
                $i++;
            }
            $order_shipping_query = $this->db->query(
                "SELECT value FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'shipping'"
            );
            if ($order_shipping_query->rows) {
                $shipping = $order_shipping_query->row["value"];
            } else {
                $shipping = 0;
            }
            $tax_query = $this->db->query(
                "SELECT value FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'tax'"
            );
            if ($tax_query->rows) {
                $tax = $tax_query->row["value"];
            } else {
                $tax = 0;
            }
            if ($this->config->get("remarketing_no_shipping")) {
                $db_order >>= "total";
            }
            if (0 < $cost) {
                $db_order >>= "total";
            }
            if ($this->config->get("remarketing_max_order_value")) {
                $max_value = $this->config->get("remarketing_max_order_value");
                if (0 < (int)$max_value && (int)$max_value < (int)$db_order["total"]) {
                    $db_order["total"] = (int)$max_value;
                }
            }
            $coupon_query = $this->db->query(
                "SELECT title FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'coupon'"
            );
            if ($coupon_query->rows) {
                $coupon = $coupon_query->row["title"];
            } else {
                $coupon = false;
            }
            $order_info = $this->model_checkout_order->getOrder($db_order["order_id"]);
            if (strpos($order_info["email"], "localhost") !== false || strpos(
                    $order_info["email"],
                    "empty"
                ) !== false || strpos(
                    $order_info["email"],
                    "noemail"
                ) !== false || !empty($this->session->data["quick_order"]) && !$this->config->get(
                    "remarketing_reviews_quick_order_status"
                )) {
                $db_order["email"] = "";
                $order_info["email"] = "";
            }
            $order_info["telephone"] = $this->phoneClear($order_info["telephone"]);
            $ga4_total_value = $this->currency->format(
                $db_order["total"] * $ga4_price_ratio,
                $this->ga4_currency,
                "",
                false
            );
            $ga4_event = [
                "send_to" => $this->config->get("remarketing_ga4_identifier"),
                "transaction_id" => $order_info["order_id"],
                "value" => (double)$ga4_total_value,
                "currency" => $this->ga4_currency,
                "affiliation" => $order_info["store_name"],
                "shipping" => $this->currency->format($shipping, $this->ga4_currency, "", false),
                "shipping_tier" => $order_info["shipping_method"],
                "payment_type" => $order_info["payment_method"],
                "tax" => (double)$this->currency->format($tax, $this->ga4_currency, "", false),
                "items" => $ga4_products
            ];
            if ($coupon) {
                $ga4_event["coupon"] = $coupon;
            }
            $ga4_datalayer = ["event" => "ga4_purchase", "ecommerce" => $ga4_event];
            if ($this->config->get("remarketing_ga4_dl_remove_prefix")) {
                $ga4_datalayer["event"] = "purchase";
            }
            if ($this->config->get("remarketing_ga4_dl_netpeak")) {
                $ga4_datalayer["value"] = $ga4_total_value;
                $ga4_datalayer["items"] = $ads_products;
            }
            if (!empty($this->session->data["quick_order"]) && $this->config->get(
                    "remarketing_ga4_quick_order_event_name"
                )) {
                $ga4_event_name = $this->config->get("remarketing_ga4_quick_order_event_name");
            }
            $client_data["event"] = "client_data";
            if ($order_info["shipping_method"]) {
                $ga4_datalayer["shipping_tier"] = $order_info["shipping_method"];
            }
            if ($order_info["payment_method"]) {
                $ga4_datalayer["payment_type"] = $order_info["payment_method"];
            }
            if ($order_info["email"]) {
                $ga4_datalayer["user_mail"] = $order_info["email"];
                $client_data["email"] = $order_info["email"];
            }
            if ($order_info["telephone"]) {
                $ga4_datalayer["user_phone"] = $order_info["telephone"];
                $client_data["phone"] = $order_info["telephone"];
            }
            unset($ga4_datalayer["ecommerce"]["send_to"]);
            if ($this->snapchat_status) {
                $snapchat_event = [
                    "transaction_id" => $order_info["order_id"],
                    "currency" => $this->snapchat_currency,
                    "item_ids" => $snapchat_item_ids,
                    "number_items" => count($snapchat_item_ids),
                    "price" => $this->currency->format(
                        $db_order["total"] * $snapchat_price_ratio,
                        $this->snapchat_currency,
                        "",
                        false
                    ),
                    "customer_status" => 0 < $order_info["customer_id"] ? "returning" : "new",
                    "success" => "1"
                ];
            }
            if ($this->uet_status) {
                $uet_event = [
                    "transaction_id" => $order_info["order_id"],
                    "ecomm_prodid" => $uet_product_ids,
                    "ecomm_pagetype" => "purchase",
                    "ecomm_totalvalue" => $ga4_total_value,
                    "revenue_value" => $ga4_total_value,
                    "currency" => $this->ga4_currency,
                    "items" => $uet_items
                ];
            }
            if ($order_info["email"] && $this->config->get("remarketing_reviews_status")) {
                $reviews_event = [
                    "merchant_id" => $this->config->get("remarketing_google_merchant_identifier"),
                    "order_id" => $order_info["order_id"],
                    "email" => $order_info["email"],
                    "delivery_country" => $this->config->get("remarketing_reviews_country"),
                    "estimated_delivery_date" => date(
                        "Y-m-d",
                        time() + 86400 * (int)($this->config->get("remarketing_reviews_date") ? $this->config->get(
                            "remarketing_reviews_date"
                        ) : 3)
                    ),
                    "opt_in_style" => "CENTER_DIALOG"
                ];
                if ($this->config->get("remarketing_reviews_feed_gtin")) {
                    $reviews_event["products"] = [];
                    foreach ($products as $product) {
                        if (!empty($product["product_info"][$this->config->get("remarketing_reviews_feed_gtin")])) {
                            $reviews_event["products"][] = [
                                "gtin" => $product["product_info"][$this->config->get(
                                    "remarketing_reviews_feed_gtin"
                                )]
                            ];
                        }
                    }
                }
            }
            if ($this->ads_status) {
                $ads_event = [
                    "send_to" => $this->config->get("remarketing_google_identifier"),
                    "value" => $this->currency->format(
                        $db_order["total"] * $ads_price_ratio,
                        $this->ads_currency,
                        "",
                        false
                    ),
                    "items" => $ads_products
                ];
                $merchant_id = $this->config->get("remarketing_google_merchant_identifier");
                if (!empty($merchant_id)) {
                    $ads_event["aw_merchant_id"] = $merchant_id;
                }
                if ($conversion_id = $this->config->get("remarketing_google_ads_identifier")) {
                    if (!empty($this->session->data["quick_order"]) && $this->config->get(
                            "remarketing_google_ads_quick_order_identifier"
                        )) {
                        $conversion_id = $this->config->get("remarketing_google_ads_quick_order_identifier");
                    }
                    $ads_conversion = [
                        "send_to" => $conversion_id,
                        "currency" => $this->ads_currency,
                        "value" => $this->currency->format(
                            $db_order["total"] * $ads_price_ratio,
                            $this->ads_currency,
                            "",
                            false
                        ),
                        "transaction_id" => $order_info["order_id"]
                    ];
                }
            }
            if ($this->fb_status) {
                $fb_total_price_formatted = $this->currency->format(
                    $db_order["total"] * $fb_price_ratio,
                    $this->fb_currency,
                    "",
                    false
                );
                $fb_send_event_flag = true;
                $fb_lead_send_flag = true;
                $fb_event = [
                    "content_type" => "product",
                    "value" => $fb_total_price_formatted,
                    "currency" => $this->fb_currency,
                    "content_ids" => $fb_content_ids,
                    "contents" => $fb_contents,
                    "num_items" => count($fb_content_ids)
                ];
                if ($this->config->get("remarketing_facebook_server_side")) {
                    $fb_capi_event = $fb_event;
                    $facebook_send_status = $this->config->get("remarketing_facebook_send_status");
                    if (is_array($facebook_send_status) && !in_array(
                            $order_info["order_status_id"],
                            $facebook_send_status
                        )) {
                        $fb_send_event_flag = false;
                    }
                    $facebook_lead_send_status = $this->config->get("remarketing_facebook_lead_send_status");
                    if (is_array($facebook_lead_send_status) && !in_array(
                            $order_info["order_status_id"],
                            $facebook_lead_send_status
                        )) {
                        $fb_lead_send_flag = false;
                    }
                }
                if (!$fb_send_event_flag) {
                    $fb_event = false;
                }
                if ($this->config->get("remarketing_facebook_lead") && $fb_lead_send_flag) {
                    $fb_lead_event = ["value" => $fb_total_price_formatted, "currency" => $this->fb_currency];
                }
            }
            if ($this->tiktok_status) {
                $tiktok_send_flag = true;
                $tiktok_event = [
                    "content_type" => "product",
                    "value" => $this->currency->format(
                        $db_order["total"] * $tiktok_price_ratio,
                        $this->tiktok_currency,
                        "",
                        false
                    ),
                    "currency" => $this->tiktok_currency,
                    "num_items" => count($tiktok_content_ids),
                    "content_ids" => $tiktok_content_ids,
                    "contents" => $tiktok_contents
                ];
                if ($this->config->get("remarketing_tiktok_server_side")) {
                    $tiktok_api_event = $tiktok_event;
                    $tiktok_send_status = $this->config->get("remarketing_tiktok_send_status");
                    if (is_array($tiktok_send_status) && !in_array(
                            $order_info["order_status_id"],
                            $tiktok_send_status
                        )) {
                        $tiktok_send_flag = false;
                    }
                }
                if (!$tiktok_send_flag) {
                    $tiktok_event = false;
                }
            }
            $esputnik_customer_data = false;
            if ($order_info["email"]) {
                $esputnik_customer_data["email"] = $order_info["email"];
            }
            if ($order_info["telephone"]) {
                $esputnik_customer_data["phone_number"] = $order_info["telephone"];
            }
            if ($order_info["firstname"]) {
                $esputnik_customer_data["first_name"] = $order_info["firstname"];
            }
            if ($order_info["lastname"]) {
                $esputnik_customer_data["last_name"] = $order_info["lastname"];
            }
            if ($order_info["shipping_address_1"]) {
                $esputnik_customer_data["home_address"] = [
                    "street" => $order_info["shipping_address_1"],
                    "city" => $order_info["shipping_city"],
                    "region" => $order_info["shipping_zone"],
                    "country" => $order_info["shipping_country"]
                ];
            }
            if ($this->esputnik_status && isset($this->session->data["remarketing_esputnik_cart_id"])) {
                $esputnik_event = [
                    "OrderNumber" => $order_info["order_id"],
                    "PurchasedItems" => $esputnik_purchased_items,
                    "GUID" => $this->session->data["remarketing_esputnik_cart_id"]
                ];
                $this->config->get("remarketing_esputnik_external_id");
                switch ($this->config->get("remarketing_esputnik_external_id")) {
                    case "email":
                        $external_customer_id = $order_info["email"];
                        break;
                    case "telephone":
                        $external_customer_id = $this->phoneClear($order_info["telephone"], true);
                        break;
                    case "customer_id":
                        $external_customer_id = 0 < $order_info["customer_id"] ? $order_info["customer_id"] : $order_info["email"];
                        break;
                    default:
                        $external_customer_id = $order_info["email"];
                        break;
                }
                $esputnik_customer_data_extra["CustomerData"] = [
                    "user_phone" => $this->phoneClear(
                        $order_info["telephone"],
                        true
                    ),
                    "user_name" => $order_info["firstname"] . ($order_info["lastname"] ? " " . $order_info["lastname"] : ""),
                    "externalCustomerId" => $external_customer_id
                ];
                if ($order_info["email"]) {
                    $esputnik_customer_data_extra["CustomerData"]["user_email"] = $order_info["email"];
                }
            }
            $order_data = [
                "order_id" => $db_order["order_id"],
                "store_name" => addslashes($db_order["store_name"]),
                "email" => $order_info["email"],
                "telephone" => $this->phoneClear($order_info["telephone"]),
                "firstname" => $db_order["firstname"],
                "lastname" => $db_order["lastname"],
                "products" => $products,
                "ga4_products" => $ga4_products,
                "order_info" => $order_info,
                "client_data" => $client_data,
                "ec_data" => $esputnik_customer_data,
                "total" => $db_order["total"],
                "default_total" => $this->currency->format(
                    $db_order["total"],
                    $this->session->data["currency"],
                    "",
                    false
                ),
                "shipping" => $this->currency->format($shipping, $this->ga4_currency, "", false),
                "tax" => $this->currency->format($tax, $this->ga4_currency, "", false),
                "coupon" => $coupon,
                "order_status_id" => $db_order["order_status_id"],
                "currency_code" => $db_order["currency_code"],
                "reviews_event" => $reviews_event,
                "ads_event" => $ads_event,
                "ads_conversion" => $ads_conversion,
                "ga4_event_name" => $ga4_event_name,
                "ga4_event" => $ga4_event,
                "ga4_datalayer" => $ga4_datalayer,
                "fb_event" => $fb_event,
                "fb_capi_event" => $fb_capi_event,
                "fb_lead_event" => $fb_lead_event,
                "snapchat_event" => $snapchat_event,
                "tiktok_event" => $tiktok_event,
                "tt_mapi_event" => $tiktok_api_event,
                "uet_event" => $uet_event,
                "esputnik_event" => $esputnik_event,
                "es_cd_event" => $esputnik_customer_data_extra
            ];
            $remarketing_orders_query = $this->db->query(
                "SELECT * FROM `" . DB_PREFIX . "remarketing_orders` WHERE order_id = '" . (int)$order_id . "'"
            );
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
                "first_referrer",
                "last_referrer"
            ];
            if (!$remarketing_orders_query->num_rows) {
                $this->db->query(
                    "INSERT INTO `" . DB_PREFIX . "remarketing_orders` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW()"
                );
                foreach ($parameters as $parameter) {
                    if (!empty($this->session->data[$parameter])) {
                        $this->db->query(
                            "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `" . $parameter . "` = '" . $this->db->escape(
                                $this->session->data[$parameter]
                            ) . "' WHERE order_id = '" . (int)$order_id . "'"
                        );
                    }
                }
                $event_id = $this->model_tool_remarketing->genEventId();
                $this->db->query(
                    "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `fb_event_id` = '" . $this->db->escape(
                        $event_id
                    ) . "' WHERE order_id = '" . (int)$order_id . "'"
                );
                $this->db->query(
                    "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `tt_event_id` = '" . $this->db->escape(
                        $event_id
                    ) . "' WHERE order_id = '" . (int)$order_id . "'"
                );
                $this->db->query(
                    "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `fb_lead_event_id` = '" . $this->db->escape(
                        $this->model_tool_remarketing->genEventId()
                    ) . "' WHERE order_id = '" . (int)$order_id . "'"
                );
            }
            $remarketing_orders_data = $this->db->query(
                "SELECT * FROM `" . DB_PREFIX . "remarketing_orders` WHERE `order_id` = '" . (int)$order_id . "'"
            );
            $order_data["sent_data"] = [];
            if ($remarketing_orders_data->rows) {
                $order_data["sent_data"] = $remarketing_orders_data->row;
            }
            foreach ($order_data["sent_data"] as $key => $val) {
                if (!empty($order_data["sent_data"][$key]) && in_array($key, $parameters)) {
                    $this->session->data[$key] = $val;
                }
            }
            if (empty($this->session->data["uuid"])) {
                $this->getCid();
            }
            if (false) {
                $this->db->query(
                    "DELETE FROM `" . DB_PREFIX . "remarketing_orders` WHERE `date_added` < DATE_SUB(NOW(), INTERVAL 6 MONTH)"
                );
            }
            return $order_data;
        } else {
            return false;
        }
    }

    public function processOrder($order_id = 0, $order_status_id = 0, $order_info = [])
    {
        $fb_currency_code = $this->config->get("remarketing_facebook_currency");
        $tiktok_currency = $this->config->get("remarketing_tiktok_currency");
        $order_remarketing_data = $this->getOrderRemarketing($order_id);
        if ($this->config->get("remarketing_ga4_mp_status") || $this->config->get("remarketing_ga4_only_purchase")) {
            $ga4_send_status = $this->config->get("remarketing_ga4_send_status");
            $ga4_refund_status = $this->config->get("remarketing_ga4_refund_status");
            $event_name = false;
            if (is_array($ga4_send_status) && in_array(
                    $order_status_id,
                    $ga4_send_status
                ) && $order_remarketing_data["sent_data"]["ga4"] == "0000-00-00 00:00:00" || $this->config->get(
                    "remarketing_ga4_resend_status"
                ) != "0" && $this->config->get("remarketing_ga4_resend_status") == $order_status_id) {
                $event_name = $order_remarketing_data["ga4_event_name"];
            }
            if (is_array($ga4_refund_status) && in_array($order_status_id, $ga4_refund_status)) {
                $event_name = "refund";
            }
            if ($event_name) {
                unset($order_remarketing_data["ga4_event"]["send_to"]);
                $ecommerce_data = [
                    "events" => [
                        [
                            "name" => $event_name,
                            "params" => $order_remarketing_data["ga4_event"]
                        ]
                    ]
                ];
                $this->sendGa4($ecommerce_data, $order_remarketing_data);
                $this->setSend($order_id, "ga4");
            }
        }
        $facebook_send_status = $this->config->get("remarketing_facebook_send_status");
        if ($this->config->get("remarketing_facebook_status") && $this->config->get(
                "remarketing_facebook_server_side"
            ) && ($this->config->get("remarketing_facebook_resend_status") != "0" && $this->config->get(
                    "remarketing_facebook_resend_status"
                ) == $order_status_id || is_array($facebook_send_status) && in_array(
                    $order_status_id,
                    $facebook_send_status
                ) && $order_remarketing_data["sent_data"]["facebook"] == "0000-00-00 00:00:00")) {
            $facebook_data["event_name"] = "Purchase";
            $facebook_data["custom_data"] = $order_remarketing_data["fb_capi_event"];
            $facebook_data["time"] = time();
            $facebook_data["event_id"] = $order_remarketing_data["sent_data"]["fb_event_id"];
            $this->sendFacebook($facebook_data, $order_remarketing_data);
            $this->setSend($order_id, "facebook");
        }
        $facebook_lead_send_status = $this->config->get("remarketing_facebook_lead_send_status");
        if ($this->config->get("remarketing_facebook_status") && $this->config->get(
                "remarketing_facebook_server_side"
            ) && is_array($facebook_lead_send_status) && in_array(
                $order_status_id,
                $facebook_lead_send_status
            ) && $order_remarketing_data["sent_data"]["facebook_lead"] == "0000-00-00 00:00:00") {
            $facebook_data = [];
            $facebook_data["event_name"] = "Lead";
            $facebook_data["custom_data"] = $order_remarketing_data["fb_lead_event"];
            $facebook_data["time"] = time();
            $facebook_data["event_id"] = $order_remarketing_data["sent_data"]["fb_lead_event_id"];
            $this->sendFacebook($facebook_data, $order_remarketing_data);
            $this->setSend($order_id, "facebook_lead");
        }
        $tiktok_send_status = $this->config->get("remarketing_tiktok_send_status");
        if ($this->config->get("remarketing_tiktok_status") && $this->config->get(
                "remarketing_tiktok_server_side"
            ) && $this->config->get("remarketing_tiktok_token") && ($this->config->get(
                    "remarketing_tiktok_resend_status"
                ) != "0" && $this->config->get("remarketing_tiktok_resend_status") == $order_status_id || is_array(
                    $tiktok_send_status
                ) && in_array(
                    $order_status_id,
                    $tiktok_send_status
                ) && $order_remarketing_data["sent_data"]["tiktok"] == "0000-00-00 00:00:00")) {
            $tiktok_data["event_name"] = "Purchase";
            $tiktok_data["properties"] = $order_remarketing_data["tt_mapi_event"];
            $tiktok_data["event_id"] = $order_remarketing_data["sent_data"]["tt_event_id"];
            $tiktok_data["url"] = $this->url->link("common/home");
            $this->sendTiktok($tiktok_data, $order_remarketing_data);
            $this->setSend($order_id, "tiktok");
        }
        if ($this->config->get("remarketing_telegram_status")) {
            $telegram_send_status = $this->config->get("remarketing_telegram_send_status");
            if (is_array($telegram_send_status) && in_array(
                    $order_status_id,
                    $telegram_send_status
                ) && $order_remarketing_data["sent_data"]["telegram"] == "0000-00-00 00:00:00") {
                $this->model_tool_remarketing->sendTelegram($order_id);
                $this->setSend($order_id, "telegram");
            }
        }
        if ($this->config->get("remarketing_esputnik_status")) {
            $event_type = false;
            $esputnik_status = false;
            $esputnik_initialized_status = $this->config->get("remarketing_esputnik_initialized_status");
            if (is_array($esputnik_initialized_status) && in_array(
                    $order_status_id,
                    $esputnik_initialized_status
                ) && $order_remarketing_data["sent_data"]["esputnik"] == "0000-00-00 00:00:00") {
                $event_type = "orderCreated";
                $esputnik_status = "INITIALIZED";
            }
            $in_progress_status = $this->config->get("remarketing_esputnik_inprogress_status");
            if (is_array($in_progress_status) && in_array($order_status_id, $in_progress_status)) {
                $event_type = "orderUpdated";
                $esputnik_status = "IN_PROGRESS";
            }
            $esputnik_delivered_status = $this->config->get("remarketing_esputnik_delivered_status");
            if (is_array($esputnik_delivered_status) && in_array($order_status_id, $esputnik_delivered_status)) {
                $event_type = "orderDelivered";
                $esputnik_status = "DELIVERED";
            }
            $esputnik_cancelled_status = $this->config->get("remarketing_esputnik_cancelled_status");
            if (is_array($esputnik_cancelled_status) && in_array($order_status_id, $esputnik_cancelled_status)) {
                $event_type = "orderCancelled";
                $esputnik_status = "CANCELLED";
            }
            if ($event_type && $esputnik_status && !empty($order_remarketing_data["email"])) {
                $event = new stdClass();
                $event->eventTypeKey = $event_type;
                $event->keyValue = $order_remarketing_data["email"];
                $event->params = [];
                $event->params[] = [
                    "name" => "phone",
                    "value" => $this->phoneClear($order_remarketing_data["telephone"], true)
                ];
                $event->params[] = ["name" => "externalOrderId", "value" => $order_remarketing_data["order_id"]];
                $this->config->get("remarketing_esputnik_external_id");
                switch ($this->config->get("remarketing_esputnik_external_id")) {
                    case "email":
                        $external_customer_id = $order_remarketing_data["email"];
                        break;
                    case "telephone":
                        $external_customer_id = $this->phoneClear($order_remarketing_data["telephone"], true);
                        break;
                    case "customer_id":
                        $external_customer_id = 0 < $order_remarketing_data["order_info"]["customer_id"] ? $order_remarketing_data["order_info"]["customer_id"] : $order_remarketing_data["email"];
                        break;
                    default:
                        $external_customer_id = $order_remarketing_data["email"];
                        break;
                }
                $event->params[] = ["name" => "externalCustomerId", "value" => $external_customer_id];
                $event->params[] = [
                    "name" => "totalCost",
                    "value" => $this->currency->format(
                        $order_remarketing_data["total"],
                        $this->esputnik_currency,
                        "",
                        false
                    )
                ];
                $event->params[] = ["name" => "status", "value" => $esputnik_status];
                $event->params[] = ["name" => "date", "value" => date("Y-m-d\\TH:i:s") . "+02:00"];
                $event->params[] = ["name" => "email", "value" => $order_remarketing_data["email"]];
                if (!empty($order_remarketing_data["firstname"])) {
                    $event->params[] = ["name" => "firstName", "value" => $order_remarketing_data["firstname"]];
                }
                if (!empty($order_remarketing_data["lastname"])) {
                    $event->params[] = ["name" => "lastName", "value" => $order_remarketing_data["lastname"]];
                }
                if (!empty(
                $order_remarketing_data["order_info"][$this->config->get(
                    "remarketing_esputnik_ttn_field"
                )]
                )) {
                    $event->params[] = [
                        "name" => "ttn",
                        "value" => $order_remarketing_data["order_info"][$this->config->get(
                            "remarketing_esputnik_ttn_field"
                        )]
                    ];
                }
                $event->params[] = ["name" => "currency", "value" => $this->session->data["currency"]];
                if ($order_remarketing_data["shipping"]) {
                    $event->params[] = ["name" => "shipping", "value" => $order_remarketing_data["shipping"]];
                }
                $event->params[] = ["name" => "deliveryMethod", "value" => $order_info["shipping_method"]];
                $event->params[] = ["name" => "paymentMethod", "value" => $order_info["payment_method"]];
                $format = $this->config->get("remarketing_esputnik_address_format");
                $find = [
                    "{firstname}",
                    "{lastname}",
                    "{company}",
                    "{address_1}",
                    "{address_2}",
                    "{city}",
                    "{postcode}",
                    "{zone}",
                    "{zone_code}",
                    "{country}"
                ];
                $replace = [
                    "firstname" => $order_info["shipping_firstname"],
                    "lastname" => $order_info["shipping_lastname"],
                    "company" => $order_info["shipping_company"],
                    "address_1" => $order_info["shipping_address_1"],
                    "address_2" => $order_info["shipping_address_2"],
                    "city" => $order_info["shipping_city"],
                    "postcode" => $order_info["shipping_postcode"],
                    "zone" => $order_info["shipping_zone"],
                    "zone_code" => $order_info["shipping_zone_code"],
                    "country" => $order_info["shipping_country"]
                ];
                $esputnik_address_formatted = str_replace(["\r\n", "\r", "\n"],
                    "<br />",
                    preg_replace(["/\\s\\s+/", "/\r\r+/", "/\n\n+/"],
                        "<br />",
                        trim(str_replace($find, $replace, $format))));
                $event->params[] = ["name" => "deliveryAddress", "value" => $esputnik_address_formatted];
                $items = [];
                $this->load->model("tool/image");
                foreach ($order_remarketing_data["products"] as $product) {
                    if ($product["product_info"]["image"]) {
                        $product_image = $this->model_tool_image->resize($product["product_info"]["image"], 200, 200);
                    } else {
                        $product_image = $this->model_tool_image->resize("no_image.jpg", 200, 200);
                    }
                    $items[] = [
                        "externalItemId" => $product["product_id"],
                        "name" => $product["name"],
                        "category" => $product["category"],
                        "quantity" => $product["quantity"],
                        "cost" => $this->currency->format($product["price"], $this->esputnik_currency, "", false),
                        "url" => $this->url->link("product/product", "product_id=" . $product["product_id"]),
                        "imageUrl" => $product_image
                    ];
                }
                if (!isset($this->session->data["esputnik_uniq"])) {
                    $this->session->data["esputnik_uniq"] = uniqid();
                }
                $products_array = ["array" => $items];
                $event->params[] = ["name" => "recycleStateId", "value" => $this->session->data["esputnik_uniq"]];
                $event->params[] = ["name" => "items", "value" => json_encode($items, JSON_UNESCAPED_UNICODE)];
                $event->params[] = [
                    "name" => "products",
                    "value" => json_encode($products_array, JSON_UNESCAPED_UNICODE)
                ];
                $this->sendEsputnik($event);
                if ($esputnik_status == "INITIALIZED") {
                    $this->setSend($order_id, "esputnik");
                    $esputnik_api_url = "https://esputnik.com/api/v1/contacts";
                    $esputnik_contacts_data = new stdClass();
                    $contact = new stdClass();
                    $esputnik_contacts_data->contacts = [];
                    $esputnik_contacts_data->dedupeOn = "email";
                    $esputnik_contacts_data->eventKeyForNewContacts = "remarketing";
                    $contact->channels = [
                        ["type" => "email", "value" => $order_remarketing_data["email"]],
                        ["type" => "sms", "value" => $this->phoneClear($order_remarketing_data["telephone"], true)]
                    ];
                    $contact->firstName = $order_remarketing_data["firstname"];
                    $contact->lastName = $order_remarketing_data["lastname"];
                    $contact->externalCustomerId = $external_customer_id;
                    if (0 < $order_remarketing_data["order_info"]["customer_id"]) {
                        $contact->id = $order_remarketing_data["order_info"]["customer_id"];
                    }
                    $esputnik_contacts_data->contacts[] = $contact;
                    $this->sendEsputnik($esputnik_contacts_data, $esputnik_api_url);
                }
            }
        }
    }

    public function getCid()
    {
        $cid = "";
        if (isset($this->request->cookie["_ga"])) {
            $cookie = explode(".", $this->request->cookie["_ga"]);
            if (isset($cookie[2]) && isset($cookie[3])) {
                $uuid = $cookie[2] . "." . $cookie[3];
                $cid = $uuid;
            }
        } elseif (isset($this->request->cookie["__utma"])) {
            $cookie = explode(".", $this->request->cookie["__utma"]);
            if (isset($cookie[1]) && isset($cookie[2])) {
                $uuid = $cookie[1] . "." . $cookie[2];
                $cid = $uuid;
            }
        } elseif (isset($this->request->cookie["remarketing_cid"])) {
            $cid = $this->request->cookie["remarketing_cid"];
        } else {
            $cid = sprintf(
                "%04x%04x-%04x-%04x-%04x-%04x%04x%04x",
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 4095) | 16384,
                mt_rand(0, 16383) | 32768,
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535)
            );
            setcookie("remarketing_cid", $cid, time() + 2592000, "/");
        }
        $this->session->data["uuid"] = $cid;
        $ga4_client_session_id = "";
        $cookie_name = "_ga_" . str_replace("G-", "", $this->config->get("remarketing_ga4_identifier"));
        if (isset($this->request->cookie[$cookie_name])) {
            $parts = explode(".", $this->request->cookie[$cookie_name]);
            if (isset($parts[2])) {
                $cookie_ga4_part = $parts[2];
                if (strpos($cookie_ga4_part, "s") === 0 && strpos($cookie_ga4_part, "\$") !== false) {
                    $cookie_ga4_parts = explode("\$", $cookie_ga4_part);
                    if (isset($cookie_ga4_parts[0])) {
                        $ga4_client_session_id = substr($cookie_ga4_parts[0], 1);
                        $this->session->data["ga4_uuid"] = $ga4_client_session_id;
                    }
                } else {
                    $ga4_client_session_id = $cookie_ga4_part;
                    $this->session->data["ga4_uuid"] = $ga4_client_session_id;
                }
            }
            setcookie("remarketing_ga4_cid", $ga4_client_session_id, time() + 2592000, "/");
        } elseif (isset($this->request->cookie["remarketing_ga4_cid"])) {
            $ga4_client_session_id = $this->request->cookie["remarketing_ga4_cid"];
            $this->session->data["ga4_uuid"] = $ga4_client_session_id;
        } else {
            $ga4_client_session_id = $this->session->data["uuid"];
            $this->session->data["ga4_uuid"] = $ga4_client_session_id;
        }
    }

    public function trackUtm()
    {
        $get_params = [
            "gclid",
            "dclid",
            "utm_source",
            "utm_campaign",
            "utm_term",
            "utm_medium",
            "utm_content",
            "utm_referrer",
            "ttclid"
        ];
        foreach ($get_params as $get_param) {
            if (isset($this->request->get[$get_param])) {
                $this->session->data[$get_param] = $this->request->get[$get_param];
            }
        }
        if (empty($this->session->data["fbp"])) {
            $this->session->data["fbp"] = "fb.1." . round(microtime(true) * 1000) . "." . mt_rand(0, 1000000000);
        }
        if (isset($this->request->cookie["_fbp"])) {
            $this->session->data["fbp"] = $this->request->cookie["_fbp"];
        }
        if (empty($this->session->data["fbc"]) && isset($this->request->get["fbclid"])) {
            $this->session->data["fbc"] = "fb.1." . round(microtime(true) * 1000) . "." . $this->request->get["fbclid"];
        }
        if (isset($this->request->cookie["_fbc"])) {
            $this->session->data["fbc"] = $this->request->cookie["_fbc"];
        }
        if ($this->config->get("remarketing_esputnik_status") && $this->customer->isLogged()) {
            if (empty($this->session->data["esputnik_email"]) && $this->customer->getEmail()) {
                $this->session->data["esputnik_email"] = $this->customer->getEmail();
            }
            if (empty($this->session->data["esputnik_telephone"]) && $this->customer->getTelephone()) {
                $this->session->data["esputnik_telephone"] = $this->phoneClear($this->customer->getTelephone(), true);
            }
            if (empty($this->session->data["esputnik_uniq"])) {
                $this->session->data["esputnik_uniq"] = uniqid();
            }
            if (empty($this->session->data["esputnik_general_info"])) {
                $this->config->get("remarketing_esputnik_external_id");
                switch ($this->config->get("remarketing_esputnik_external_id")) {
                    case "email":
                        $external_customer_id = $this->customer->getEmail();
                        break;
                    case "telephone":
                        $external_customer_id = $this->phoneClear($this->customer->getTelephone(), true);
                        break;
                    case "customer_id":
                        $external_customer_id = $this->customer->isLogged();
                        break;
                    default:
                        $external_customer_id = $this->customer->getEmail();
                        break;
                }
                $esputnik_general_info = [
                    "externalCustomerId" => $external_customer_id,
                    "user_email" => $this->customer->getEmail(),
                    "user_name" => $this->customer->getFirstname() . ($this->customer->getLastName(
                        ) ? " " . $this->customer->getLastName() : ""),
                    "user_client_id" => $this->customer->isLogged(),
                    "user_phone" => $this->phoneClear($this->customer->getTelephone(), true)
                ];
                $this->session->data["esputnik_general_info"] = $esputnik_general_info;
                $this->session->data["esputnik_general_info_string"] = json_encode(
                    $esputnik_general_info,
                    JSON_UNESCAPED_UNICODE
                );
            }
        } else {
            unset($this->session->data["esputnik_email"]);
            unset($this->session->data["esputnik_telephone"]);
            unset($this->session->data["esputnik_uniq"]);
            unset($this->session->data["esputnik_general_info"]);
            unset($this->session->data["esputnik_general_info_string"]);
        }
        if (!headers_sent()) {
            $last_referrer = "Direct";
            if (!isset($this->request->cookie["first_referrer"])) {
                if (!empty($this->request->get["referrer"])) {
                    $first_referrer = $this->request->get["referrer"];
                } elseif (!empty($this->request->server["HTTP_REFERER"]) && !strpos(
                        $this->request->server["HTTP_REFERER"],
                        $this->request->server["SERVER_NAME"]
                    )) {
                    $first_referrer = parse_url($this->request->server["HTTP_REFERER"], PHP_URL_HOST);
                } else {
                    $first_referrer = "Direct";
                }
                setcookie("first_referrer", $first_referrer, time() + 31536000, "/");
                $this->session->data["first_referrer"] = $first_referrer;
            } else {
                $this->session->data["first_referrer"] = $this->request->cookie["first_referrer"];
            }
            if (!isset($this->request->cookie["last_referrer"])) {
                if (!empty($this->request->get["referrer"])) {
                    $last_referrer = $this->request->get["referrer"];
                } elseif (!empty($this->request->server["HTTP_REFERER"]) && !strpos(
                        $this->request->server["HTTP_REFERER"],
                        $this->request->server["SERVER_NAME"]
                    )) {
                    $last_referrer = parse_url($this->request->server["HTTP_REFERER"], PHP_URL_HOST);
                } else {
                    $last_referrer = "Direct";
                }
                setcookie("last_referrer", $last_referrer, time() + 31536000, "/");
                $this->session->data["last_referrer"] = $last_referrer;
            } elseif (!empty($this->request->server["HTTP_REFERER"]) && !strpos(
                    $this->request->server["HTTP_REFERER"],
                    $this->request->server["SERVER_NAME"]
                ) && parse_url(
                    $this->request->server["HTTP_REFERER"],
                    PHP_URL_HOST
                ) != $this->request->cookie["last_referrer"]) {
                setcookie(
                    "last_referrer",
                    parse_url($this->request->server["HTTP_REFERER"], PHP_URL_HOST),
                    time() + 31536000,
                    "/"
                );
                $this->session->data["last_referrer"] = parse_url($this->request->server["HTTP_REFERER"], PHP_URL_HOST);
            } else {
                $this->session->data["last_referrer"] = $this->request->cookie["last_referrer"];
            }
        }
    }

    public function sendGa4($ecommerce_data = [], $order_info = [])
    {
        if ($this->config->get("remarketing_ga4_mp_status") && $this->config->get(
                "remarketing_ga4_analytics_id"
            ) && $this->config->get("remarketing_ga4_mp_api_secret") && !empty($this->session->data["uuid"])) {
            if (!empty($this->request->post["ga4_data"])) {
                $ecommerce_data = $this->request->post["ga4_data"];
            }
            $ecommerce_data["client_id"] = $this->session->data["uuid"];
            if (empty($ecommerce_data["events"][0]["params"]["session_id"]) && !empty($this->session->data["ga4_uuid"])) {
                $ecommerce_data["events"][0]["params"]["session_id"] = $this->session->data["ga4_uuid"];
            }
            if (!empty($order_info["sent_data"]["uuid"])) {
                $ecommerce_data["client_id"] = $order_info["sent_data"]["uuid"];
            }
            if (!empty($order_info["sent_data"]["ga4_uuid"])) {
                $ecommerce_data["events"][0]["params"]["session_id"] = $order_info["sent_data"]["ga4_uuid"];
            }
            $ecommerce_data["user_data"] = [];
            if ($this->customer->isLogged()) {
                $ecommerce_data["user_data"]["address"] = [];
                if ($this->customer->getEmail()) {
                    $ecommerce_data["user_data"]["sha256_email_address"] = hash(
                        "sha256",
                        mb_strtolower(trim(str_replace(".", "", $this->customer->getEmail())))
                    );
                }
                if ($this->customer->getTelephone()) {
                    $ecommerce_data["user_data"]["sha256_phone_number"] = hash(
                        "sha256",
                        $this->phoneClear($this->customer->getTelephone())
                    );
                }
                if ($this->customer->getFirstName()) {
                    $ecommerce_data["user_data"]["address"]["sha256_first_name"] = hash(
                        "sha256",
                        mb_strtolower($this->customer->getFirstName())
                    );
                }
                if ($this->customer->getLastName()) {
                    $ecommerce_data["user_data"]["address"]["sha256_last_name"] = hash(
                        "sha256",
                        mb_strtolower($this->customer->getLastName())
                    );
                }
            }
            if ($order_info) {
                if (!empty($order_info["sent_data"]["utm_campaign"])) {
                    $ecommerce_data["events"][0]["params"]["campaign"] = $order_info["sent_data"]["utm_campaign"];
                }
                if (!empty($order_info["sent_data"]["utm_source"])) {
                    $ecommerce_data["events"][0]["params"]["source"] = $order_info["sent_data"]["utm_source"];
                }
                if (!empty($order_info["sent_data"]["utm_medium"])) {
                    $ecommerce_data["events"][0]["params"]["medium"] = $order_info["sent_data"]["utm_medium"];
                }
                if (!empty($order_info["sent_data"]["utm_term"])) {
                    $ecommerce_data["events"][0]["params"]["term"] = $order_info["sent_data"]["utm_term"];
                }
                if (!empty($order_info["sent_data"]["utm_content"])) {
                    $ecommerce_data["events"][0]["params"]["content"] = $order_info["sent_data"]["utm_content"];
                }
                $ecommerce_data["user_data"]["address"] = [];
                if (!empty($order_info["email"])) {
                    $ecommerce_data["user_data"]["sha256_email_address"] = hash(
                        "sha256",
                        mb_strtolower(trim(str_replace(".", "", $order_info["email"])))
                    );
                }
                if (!empty($order_info["telephone"])) {
                    $ecommerce_data["user_data"]["sha256_phone_number"] = hash(
                        "sha256",
                        $this->phoneClear($order_info["telephone"])
                    );
                }
                if (!empty($order_info["firstname"])) {
                    $ecommerce_data["user_data"]["address"]["sha256_first_name"] = hash(
                        "sha256",
                        mb_strtolower($order_info["firstname"])
                    );
                }
                if (!empty($order_info["lastname"])) {
                    $ecommerce_data["user_data"]["address"]["sha256_last_name"] = hash(
                        "sha256",
                        mb_strtolower($order_info["lastname"])
                    );
                }
                if (!empty($order_info["order_info"]["shipping_city"])) {
                    $ecommerce_data["user_data"]["address"]["city"] = mb_strtolower(
                        $order_info["order_info"]["shipping_city"]
                    );
                }
                if (!empty($order_info["order_info"]["shipping_postcode"])) {
                    $ecommerce_data["user_data"]["address"]["postal_code"] = trim(
                        $order_info["order_info"]["shipping_postcode"]
                    );
                }
                if (!empty($order_info["order_info"]["shipping_zone"])) {
                    $ecommerce_data["user_data"]["address"]["region"] = mb_strtolower(
                        $order_info["order_info"]["shipping_zone"]
                    );
                }
                if (!empty($order_info["order_info"]["shipping_country"])) {
                    $country_iso_query = $this->db->query(
                        "SELECT `iso_code_2` FROM " . DB_PREFIX . "country WHERE `name` = '" . $this->db->escape(
                            $order_info["order_info"]["shipping_country"]
                        ) . "' LIMIT 1"
                    );
                    if ($country_iso_query->num_rows) {
                        $ecommerce_data["user_data"]["address"]["country"] = $country_iso_query->row["iso_code_2"];
                    }
                }
            }
            if (empty($ecommerce_data["user_data"])) {
                unset($ecommerce_data["user_data"]);
            }
            $url = "https://www.google-analytics.com/mp/collect?measurement_id=" . $this->config->get(
                    "remarketing_ga4_analytics_id"
                ) . "&api_secret=" . $this->config->get("remarketing_ga4_mp_api_secret");
            $ecommerce_data_send = [];
            $ecommerce_data_send = json_encode($ecommerce_data);
            $content = $ecommerce_data_send;
            $this->writeLog("ga4", $ecommerce_data);
            $ch = curl_init();
            if (!empty($this->request->server["HTTP_USER_AGENT"])) {
                curl_setopt($ch, CURLOPT_USERAGENT, $this->request->server["HTTP_USER_AGENT"]);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                ["Content-Type:application/json", "Content-Length: " . mb_strlen($content)]
            );
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $http_code == 204;
        }
    }

    public function sendFacebook($facebook_data = [], $order_info = false)
    {
        if ($this->config->get("remarketing_facebook_token")) {
            $data = [];
            if (empty($facebook_data["custom_data"])) {
                $facebook_data["custom_data"] = [];
            }
            if (!empty($this->request->post)) {
                $need_params = ["event_name", "custom_data", "event_id", "url"];
                foreach ($this->request->post as $key => $val) {
                    if (in_array($key, $need_params)) {
                        $facebook_data[$key] = $val;
                    }
                }
            }
            $data["event_name"] = $facebook_data["event_name"];
            $data["event_id"] = time();
            $data["event_time"] = $data["event_id"];
            $data["event_source_url"] = rtrim(HTTPS_SERVER, "/") . $this->request->server["REQUEST_URI"];
            if (isset($facebook_data["url"])) {
                $data["event_source_url"] = $facebook_data["url"];
            }
            $data["custom_data"] = $facebook_data["custom_data"];
            if (isset($this->request->server["HTTP_CLIENT_IP"])) {
                $ip = $this->request->server["HTTP_CLIENT_IP"];
            } elseif (isset($this->request->server["HTTP_X_FORWARDED_FOR"])) {
                $ip = $this->request->server["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($this->request->server["HTTP_CF_CONNECTING_IP"])) {
                $ip = $this->request->server["HTTP_CF_CONNECTING_IP"];
            } else {
                $ip = $this->request->server["REMOTE_ADDR"];
            }
            $ip = strtok($ip, ",");
            $ua = "";
            if (!empty($this->request->server["HTTP_USER_AGENT"])) {
                $ua = $this->request->server["HTTP_USER_AGENT"];
            }
            $data["user_data"] = ["client_ip_address" => $ip, "client_user_agent" => $ua];
            if (isset($this->session->data["fbc"])) {
                $data["user_data"]["fbc"] = $this->session->data["fbc"];
            }
            if (isset($this->session->data["fbp"])) {
                $data["user_data"]["fbp"] = $this->session->data["fbp"];
            }
            if ($this->customer->isLogged()) {
                if ($this->customer->getEmail()) {
                    $data["user_data"]["em"] = hash("sha256", $this->customer->getEmail());
                }
                if ($this->customer->getFirstName()) {
                    $data["user_data"]["fn"] = hash("sha256", mb_strtolower($this->customer->getFirstName()));
                }
                if ($this->customer->getLastName()) {
                    $data["user_data"]["ln"] = hash("sha256", mb_strtolower($this->customer->getLastName()));
                }
                if ($this->customer->getTelephone()) {
                    $data["user_data"]["ph"] = hash("sha256", $this->phoneClear($this->customer->getTelephone()));
                }
                $data["user_data"]["external_id"] = hash("sha256", $this->customer->getEmail());
            }
            if ($order_info) {
                if (!empty($order_info["email"])) {
                    $data["user_data"]["em"] = hash("sha256", mb_strtolower(trim($order_info["email"])));
                }
                if (!empty($order_info["firstname"])) {
                    $data["user_data"]["fn"] = hash("sha256", mb_strtolower($order_info["firstname"]));
                }
                if (!empty($order_info["lastname"])) {
                    $data["user_data"]["ln"] = hash("sha256", mb_strtolower($order_info["lastname"]));
                }
                if (!empty($order_info["telephone"])) {
                    $data["user_data"]["ph"] = hash("sha256", $this->phoneClear($order_info["telephone"]));
                }
                if (!empty($order_info["order_info"]["shipping_country"])) {
                    $country_iso_query = $this->db->query(
                        "SELECT `iso_code_2` FROM " . DB_PREFIX . "country WHERE `name` = '" . $this->db->escape(
                            $order_info["order_info"]["shipping_country"]
                        ) . "' LIMIT 1"
                    );
                    if ($country_iso_query->num_rows) {
                        $data["user_data"]["country"] = hash(
                            "sha256",
                            mb_strtolower($country_iso_query->row["iso_code_2"])
                        );
                    }
                }
                if (!empty($order_info["order_info"]["shipping_city"])) {
                    $data["user_data"]["ct"] = hash("sha256", $order_info["order_info"]["shipping_city"]);
                }
                if (!empty($order_info["order_info"]["shipping_postcode"])) {
                    $data["user_data"]["zp"] = hash("sha256", $order_info["order_info"]["shipping_postcode"]);
                }
            }
            if (!empty($facebook_data["event_id"])) {
                $data["event_id"] = $facebook_data["event_id"];
            }
            $data["opt_out"] = false;
            $data["action_source"] = "website";
            $fb_data["data"] = [json_encode($data)];
            if ($this->config->get("remarketing_facebook_test_code") != "") {
                $fb_data["test_event_code"] = $this->config->get("remarketing_facebook_test_code");
            }
            $fb_send_data = http_build_query($fb_data);
            $fb_send_data = utf8_encode($fb_send_data);
            $url = "https://graph.facebook.com/v" . $this->config->get(
                    "remarketing_facebook_api_ver"
                ) . "/" . $this->config->get(
                    "remarketing_facebook_identifier"
                ) . "/events?access_token=" . $this->config->get("remarketing_facebook_token");
            $ch = curl_init();
            if (!empty($this->request->server["HTTP_USER_AGENT"])) {
                curl_setopt($ch, CURLOPT_USERAGENT, $this->request->server["HTTP_USER_AGENT"]);
            }
            $this->writeLog("facebook", $fb_data);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-type: application/x-www-form-urlencoded"]);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fb_send_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }
    }

    public function sendTiktok($tiktok_data = [], $order_info = false)
    {
        if ($this->config->get("remarketing_tiktok_token")) {
            $data = [];
            $data["pixel_code"] = $this->config->get("remarketing_tiktok_identifier");
            $data["timestamp"] = date("c");
            if (empty($tiktok_data["properties"])) {
                $tiktok_data["properties"] = [];
            }
            if (!empty($this->request->post)) {
                $need_params = ["event_name", "properties", "event_id", "url"];
                foreach ($this->request->post as $key => $val) {
                    if (in_array($key, $need_params)) {
                        $tiktok_data[$key] = $val;
                    }
                }
            }
            $data["event"] = $tiktok_data["event_name"];
            $data["event_id"] = $tiktok_data["event_id"];
            $data["properties"] = $tiktok_data["properties"];
            if ($this->config->get("remarketing_tiktok_test_code") != "") {
                $data["test_event_code"] = $this->config->get("remarketing_tiktok_test_code");
            }
            $data["context"] = [];
            $data["context"]["page"]["url"] = rtrim(HTTPS_SERVER, "/") . $this->request->server["REQUEST_URI"];
            if (isset($tiktok_data["url"])) {
                $data["context"]["page"]["url"] = $tiktok_data["url"];
            }
            if (!empty($this->session->data["ttclid"])) {
                $data["context"]["ad"]["callback"] = $this->session->data["ttclid"];
            }
            if (isset($this->request->server["HTTP_CLIENT_IP"])) {
                $ip = $this->request->server["HTTP_CLIENT_IP"];
            } elseif (isset($this->request->server["HTTP_X_FORWARDED_FOR"])) {
                $ip = $this->request->server["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($this->request->server["HTTP_CF_CONNECTING_IP"])) {
                $ip = $this->request->server["HTTP_CF_CONNECTING_IP"];
            } else {
                $ip = $this->request->server["REMOTE_ADDR"];
            }
            $ip = strtok($ip, ",");
            $ua = "";
            if (!empty($this->request->server["HTTP_USER_AGENT"])) {
                $ua = $this->request->server["HTTP_USER_AGENT"];
            }
            $data["context"]["user"] = [];
            if ($this->customer->isLogged()) {
                if ($this->customer->getEmail()) {
                    $data["context"]["user"]["email"] = hash("sha256", $this->customer->getEmail());
                }
                if ($this->customer->getTelephone()) {
                    $data["context"]["user"]["phone_number"] = hash(
                        "sha256",
                        $this->phoneClear($this->customer->getTelephone())
                    );
                }
                $data["context"]["user"]["external_id"] = hash("sha256", $this->customer->getEmail());
            }
            if (!empty($this->request->cookie["_ttp"])) {
                $data["context"]["user"]["ttp"] = $this->request->cookie["_ttp"];
            }
            $data["context"]["user_agent"] = $ua;
            $data["context"]["ip"] = $ip;
            if ($order_info) {
                if (!empty($order_info["email"])) {
                    $data["context"]["user"]["email"] = hash("sha256", $order_info["email"]);
                }
                if (!empty($order_info["telephone"])) {
                    $data["context"]["user"]["phone_number"] = hash(
                        "sha256",
                        $this->phoneClear($order_info["telephone"])
                    );
                }
            }
            if (!empty($tiktok_data["event_id"])) {
                $data["event_id"] = $tiktok_data["event_id"];
            }
            if ($this->config->get("remarketing_tiktok_test_code") != "") {
                $data["test_event_code"] = $this->config->get("remarketing_tiktok_test_code");
            }
            if (empty($data["context"]["user"])) {
                unset($data["context"]["user"]);
            }
            $t_data = json_encode($data);
            $url = "https://business-api.tiktok.com/open_api/v" . $this->config->get(
                    "remarketing_tiktok_api_ver"
                ) . "/pixel/track/";
            $ch = curl_init();
            if (isset($this->request->server["HTTP_USER_AGENT"])) {
                curl_setopt($ch, CURLOPT_USERAGENT, $this->request->server["HTTP_USER_AGENT"]);
            }
            $this->writeLog("tiktok", $t_data);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                ["Access-Token: " . $this->config->get("remarketing_tiktok_token"), "Content-Type: application/json"]
            );
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $t_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }
    }

    public function sendEsputnik($esputnik_data = [], $event_url = "https://esputnik.com/api/v1/event")
    {
        if ($this->config->get("remarketing_esputnik_api_status") && $this->config->get(
                "remarketing_esputnik_login"
            ) && $this->config->get("remarketing_esputnik_password")) {
            if (empty($esputnik_data) && !empty($this->request->post) && !empty($this->session->data["esputnik_general_info"])) {
                $need_params = ["event_name", "event_type", "event_data"];
                foreach ($this->request->post as $key => $val) {
                    if (in_array($key, $need_params)) {
                        $event_data[$key] = $val;
                    }
                }
                $event = new stdClass();
                $event->eventTypeKey = $event_data["event_name"];
                $event->keyValue = $this->session->data["esputnik_general_info"]["user_email"];
                $event->params = [];
                $event->params[] = [
                    "name" => "phone",
                    "value" => $this->session->data["esputnik_general_info"]["user_phone"]
                ];
                $event->params[] = [
                    "name" => "email",
                    "value" => $this->session->data["esputnik_general_info"]["user_email"]
                ];
                $event->params[] = ["name" => "currencyCode", "value" => $this->esputnik_currency];
                if (!empty($this->session->data["esputnik_general_info"]["external_customer_id"])) {
                    $event->params[] = [
                        "name" => "externalCustomerId",
                        "value" => $this->session->data["esputnik_general_info"]["external_customer_id"]
                    ];
                }
                $event->params[] = [
                    "name" => $event_data["event_type"],
                    "value" => json_encode($event_data["event_data"], JSON_UNESCAPED_UNICODE)
                ];
                $esputnik_data = $event;
            }
            $user = $this->config->get("remarketing_esputnik_login");
            $password = $this->config->get("remarketing_esputnik_password");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($esputnik_data, JSON_UNESCAPED_UNICODE));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                ["Accept: application/json", "Content-Type: application/json;charset=UTF-8"]
            );
            curl_setopt($ch, CURLOPT_URL, $event_url);
            curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $password);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $http_code == 200;
        }
    }

    public function sendTelegramMsg($message = "")
    {
        $telegram_api_base_url = "https://api.telegram.org/bot";
        $tg_token = $this->config->get("remarketing_telegram_bot_id");
        $telegram_send_message_url = $telegram_api_base_url . $tg_token . "/sendMessage";
        $telegram_send_to_ids = $this->config->get("remarketing_telegram_send_to_id");
        $telegram_send_to_ids_array = explode(",", $telegram_send_to_ids);
        $telegram_message_clean = "";
        $telegram_message_clean = strip_tags($message, "<a><b><i>");
        $telegram_message_clean = html_entity_decode($telegram_message_clean);
        foreach ($telegram_send_to_ids_array as $user_id) {
            $telegram_post_data = ["chat_id" => $user_id, "text" => $message, "parse_mode" => "html"];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $telegram_send_message_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $telegram_post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        if (!empty($response)) {
            return json_decode($response, true);
        }
    }

    public function setSend($order_id, $source)
    {
        if (!empty($order_id)) {
            $this->db->query(
                "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `" . $this->db->escape(
                    $source
                ) . "` = NOW() WHERE order_id = '" . (int)$order_id . "'"
            );
        }
    }

    public function setSuccessPage($order_id = 0)
    {
        if (!empty($order_id)) {
            unset($this->session->data["remarketing_esputnik_cart_id"]);
            unset($this->session->data["remarketing_order_id"]);
            unset($this->session->data["order_id"]);
            unset($this->session->data["quick_order"]);
            setcookie("remarketing_order_id", $order_id, time() - 3600, "/");
            $this->db->query(
                "UPDATE `" . DB_PREFIX . "remarketing_orders` SET `success_page` = NOW() WHERE order_id = '" . (int)$order_id . "'"
            );
        }
    }

    public function writeLog($source, $event)
    {
        if (!$this->config->get("remarketing_debug_mode")) {
            return null;
        }
        if ($event) {
            $log = new Log("remarketing_log_" . $source . "_" . date("d-m-Y") . ".log");
            $log->write(json_encode($event, JSON_UNESCAPED_UNICODE));
            $this->db->query(
                "INSERT INTO `" . DB_PREFIX . "remarketing_log` SET `source` = '" . $this->db->escape(
                    $source
                ) . "', `event` = '" . $this->db->escape(json_encode($event)) . "', date_added = NOW()"
            );
            $this->db->query(
                "DELETE FROM `" . DB_PREFIX . "remarketing_log` WHERE date_added < DATE_SUB(CURDATE(), INTERVAL 1 DAY)"
            );
        }
    }

    private function translitRuEn($string)
    {
        $string = (string)$string;
        $string = trim($string);
        $string = mb_strtolower($string);
        $string = strtr(
            $string,
            [
                "а" => "a",
                "б" => "b",
                "в" => "v",
                "г" => "g",
                "д" => "d",
                "е" => "e",
                "ё" => "e",
                "ж" => "j",
                "з" => "z",
                "и" => "i",
                "й" => "y",
                "к" => "k",
                "л" => "l",
                "м" => "m",
                "н" => "n",
                "о" => "o",
                "п" => "p",
                "р" => "r",
                "с" => "s",
                "т" => "t",
                "у" => "u",
                "ф" => "f",
                "х" => "h",
                "ц" => "c",
                "ч" => "ch",
                "ш" => "sh",
                "щ" => "shch",
                "ы" => "y",
                "э" => "e",
                "ю" => "yu",
                "я" => "ya",
                "ъ" => "",
                "ь" => ""
            ]
        );
        return $string;
    }

    public function phoneClear($telephone, $delete_plus = false)
    {
        $telephone = preg_replace("/[^0-9+]/", "", $telephone);
        if ($delete_plus) {
            $telephone = str_replace("+", "", $telephone);
        }
        return $telephone;
    }

    public function trackEvent(
        $service = "",
        $event_name = "",
        $event_data = [],
        $event_id = "",
        $server_side = false,
        $parameters = []
    ) {
        if (!$service) {
            return null;
        }
        $output = "";
        switch ($service) {
            case "ga4":
                if ($server_side) {
                    $output .= "<script>\$(document).ready(function(){ \$.ajax({type:'post',url:'index.php?route=common/remarketing/sendGa4Mp',data:" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ",dataType:'json',success:function(json){if(json['success']){}}})})</script>\n";
                } elseif (empty($parameters["add_click"])) {
                    $output .= "<script>\$(document).ready(function(){ if (typeof gtag != 'undefined') { gtag('event', '" . $event_name . "', " . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ");}})</script>\n";
                } else {
                    $output .= "<script>\$(window).ready(function(){ if(typeof gtag!='undefined'){ gtag('event', '" . $event_name . "', " . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ");if(typeof(localStorage.remarketing_heading)!=='undefined'){click_data=" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ";click_data.items[0].item_list_name=localStorage.remarketing_heading;gtag('event','select_item',click_data);delete localStorage.remarketing_product_id;delete localStorage.remarketing_heading;}}});</script>\n";
                }
                break;
            case "ga4_dl":
                if (empty($parameters["add_click"])) {
                    $output .= "<script>\$(document).ready(function(){ window.dataLayer=window.dataLayer||[];dataLayer.push({ecommerce:null});dataLayer.push(" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ")})</script>\n";
                } else {
                    $output .= "<script>\$(document).ready(function(){ window.dataLayer=window.dataLayer||[];dataLayer.push({ecommerce:null});dataLayer.push(" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ");" . (!empty($parameters["add_click"]) ? "if(typeof(localStorage.remarketing_heading)!=='undefined'){dataLayer.push({ecommerce:null});click_data=" . json_encode(
                                $parameters["add_click"],
                                JSON_UNESCAPED_UNICODE
                            ) . ";click_data.ecommerce.items[0].item_list_name=localStorage.remarketing_heading;dataLayer.push(click_data);delete localStorage.remarketing_product_id;delete localStorage.remarketing_heading;}" : "") . "});</script>\n";
                }
                break;
            case "dl":
                $output .= "<script>\$(document).ready(function(){ window.dataLayer=window.dataLayer||[];dataLayer.push(" . json_encode(
                        $event_data,
                        JSON_UNESCAPED_UNICODE
                    ) . ")})</script>\n";
                break;
            case "ads":
                $output .= "<script>\$(document).ready(function(){ if (typeof gtag != 'undefined') { gtag('event', '" . $event_name . "', " . json_encode(
                        $event_data,
                        JSON_UNESCAPED_UNICODE
                    ) . ");}})</script>\n";
                break;
            case "fb":
                if ($server_side) {
                    $output .= "<script>\$(document).ready(function(){ \$.ajax({type:'post',url:'index.php?route=common/remarketing/sendFbCapi',data:" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ",dataType:'json',success:function(json){if(json['success']){}}})})</script>\n";
                } else {
                    $output .= "<script>\$(document).ready(function(){ if (typeof fbq != 'undefined') { fbq('track', '" . $event_name . "', " . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ", {eventID: '" . $event_id . "'})}})</script>\n";
                }
                break;
            case "fb_custom":
                if ($server_side) {
                    $output .= "<script>\$(document).ready(function(){ \$.ajax({type:'post',url:'index.php?route=common/remarketing/sendFbCapi',data:" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ",dataType:'json',success:function(json){if(json['success']){}}})})</script>\n";
                } else {
                    $output .= "<script>\$(document).ready(function(){ if (typeof fbq != 'undefined') { fbq('trackCustom', '" . $event_name . "', " . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ", {eventID: '" . $event_id . "'})}})</script>\n";
                }
                break;
            case "tiktok":
                if ($server_side) {
                    $output .= "<script>\$(document).ready(function(){ \$.ajax({type:'post',url:'index.php?route=common/remarketing/sendTikTokMapi',data:" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ",dataType:'json',success:function(json){if(json['success']){}}})})</script>\n";
                } else {
                    $output .= "<script>\$(document).ready(function(){ if (typeof ttq != 'undefined') { ttq.track('" . $event_name . "', " . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ", {eventID: '" . $event_id . "'})}})</script>\n";
                }
                break;
            case "esputnik":
                if ($server_side) {
                    $output .= "<script>\$(document).ready(function(){ \$.ajax({type:'post',url:'index.php?route=common/remarketing/sendEsputnikApi',data:" . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ",dataType:'json',success:function(json){if(json['success']){}}})})</script>\n";
                } else {
                    if (!empty($this->session->data["esputnik_general_info"])) {
                        $event_data["GeneralInfo"] = $this->session->data["esputnik_general_info"];
                    }
                    $output .= "<script>\$(document).ready(function(){ if (typeof eS != 'undefined') {eS('sendEvent', '" . $event_name . "', " . json_encode(
                            $event_data,
                            JSON_UNESCAPED_UNICODE
                        ) . ")}})</script>\n";
                }
                break;
            case "snapchat":
                $output .= "<script>\$(document).ready(function(){ if (typeof snaptr != 'undefined') { snaptr('track', '" . $event_name . "', " . json_encode(
                        $event_data,
                        JSON_UNESCAPED_UNICODE
                    ) . ")};})</script>" . "\n";
                break;
            case "uet":
                $output .= "<script>\$(document).ready(function(){ window.uetq = window.uetq || []; window.uetq.push('event', '" . $event_name . "', " . json_encode(
                        $event_data,
                        JSON_UNESCAPED_UNICODE
                    ) . ")});</script>" . "\n";
                break;
            default:
                break;
        }
        if (empty($event_name) && !empty($event_data["event"])) {
            $event_name = $event_data["event"];
        }
        if ($output) {
            $output .= "<script>\$(document).ready(function(){ remarketingLog('" . $service . "_" . $event_name . ($server_side ? "_api" : "") . "')})</script>\n";
        }
        return $output;
    }

    public function getProducts($start = 0, $limit = 3000, $ocstore = false)
    {
        $feed_category_filter = $this->config->get("remarketing_feed_category");
        $feed_manufacturer_filter = $this->config->get("remarketing_feed_manufacturer");
        if (!empty($feed_category_filter)) {
            $categories = implode(",", $feed_category_filter);
        }

        if (!empty($feed_manufacturer_filter)) {
            $manufacturers = implode(",", $feed_manufacturer_filter);
        } else {
            $manufacturers = false;
        }
        $feed_in_stock_statuses = $this->config->get("remarketing_feed_export_in_stock");
        $feed_out_of_stock_statuses = $this->config->get("remarketing_feed_export_out_of_stock");
        if (!empty($feed_in_stock_statuses)) {
            $in_stock_statuses = implode(",", $feed_in_stock_statuses);
        } else {
            $in_stock_statuses = false;
        }
        if (!empty($feed_out_of_stock_statuses)) {
            $out_of_stock_statuses = implode(",", $feed_out_of_stock_statuses);
        } else {
            $out_of_stock_statuses = false;
        }
        if (!empty($this->request->get["in_stock_statuses"])) {
            $in_stock_statuses = $this->db->escape($this->request->get["in_stock_statuses"]);
        }
        if (!empty($this->request->get["out_of_stock_statuses"])) {
            $out_of_stock_statuses = $this->db->escape($this->request->get["out_of_stock_statuses"]);
        }
        $special = $this->config->get("remarketing_feed_special");
        $customer_group = (int)$this->config->get("remarketing_feed_customer_group");
        if (!empty($this->request->get["customer_group_id"])) {
            $customer_group = (int)$this->request->get["customer_group_id"];
        }
        $zero_quantity = $this->config->get("remarketing_feed_zero_quantity");
        $min_price = (int)$this->config->get("remarketing_feed_min_price");
        $max_price = (int)$this->config->get("remarketing_feed_max_price");
        $custom_sql = html_entity_decode($this->config->get("remarketing_feed_custom_sql"));
        if (!empty($this->request->get["categories"])) {
            $categories = $this->db->escape($this->request->get["categories"]);
        }
        if (!empty($this->request->get["target"]) && $this->request->get["target"] == "esputnik" && !$categories) {
            $categories = [];
        }
        if (!empty($this->request->get["manufacturers"])) {
            $manufacturers = $this->db->escape($this->request->get["manufacturers"]);
        }
        if (!empty($this->request->get["not_categories"])) {
            $custom_sql .= " AND p2c.category_id NOT IN (" . $this->db->escape(
                    $this->request->get["not_categories"]
                ) . ") ";
        }
        if (!empty($in_stock_statuses)) {
            $custom_sql .= " AND p.stock_status_id IN (" . $this->db->escape($in_stock_statuses) . ") ";
        }
        if (!empty($out_of_stock_statuses)) {
            $custom_sql .= " AND p.stock_status_id NOT IN (" . $this->db->escape($out_of_stock_statuses) . ") ";
        }
        if (!empty($this->request->get["price_from"])) {
            $min_price = $this->request->get["price_from"];
        }
        if (!empty($this->request->get["price_to"])) {
            $max_price = $this->request->get["price_to"];
        }
        $store_id = false;
        if (!empty($this->request->get["store_id"])) {
            $store_id = (int)$this->request->get["store_id"];
        }
        $sql = "SELECT p.*, pd.*, m.name AS manufacturer, " . (!isset($categories) && empty($this->request->get["not_categories"]) ? " NULL AS category_id" : " p2c.category_id ") . ", " . ($special ? " ps.price " : " NULL ") . " AS special," . ($special ? " ps.date_end " : " NULL ") . " AS special_date_end FROM " . DB_PREFIX . "product p " . ($store_id ? " LEFT JOIN " . DB_PREFIX . "product_to_store AS p2s ON (p.product_id = p2s.product_id) " : "") . (isset($categories) || !empty($this->request->get["not_categories"]) ? " JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id " . ($ocstore ? " AND p2c.main_category = 1 " : "") . ") " : " ") . " LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) " . ($special ? " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id AND ps.customer_group_id = '" . (int)$customer_group . "' AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) " : " ") . " WHERE 1 " . (isset($categories) ? " AND p2c.category_id IN (" . $this->db->escape(
                    $categories
                ) . ")" : "") . ($manufacturers ? " AND p.manufacturer_id IN (" . $this->db->escape(
                    $manufacturers
                ) . ")" : "") . " AND pd.language_id = '" . (int)$this->config->get(
                "config_language_id"
            ) . "' AND p.status = '1'" . ($zero_quantity ? " AND p.quantity > 0 " : "") . (0 < $min_price ? " AND p.price >= " . $min_price . " " : "") . (0 < $max_price ? " AND p.price <= " . $max_price . " " : "") . (!empty($custom_sql) ? " " . $custom_sql . " " : "") . ($store_id ? " AND p2s.store_id = '" . (int)$store_id . "'" : "") . " AND p.price > 0 AND p.image <> '' AND p.image IS NOT NULL GROUP BY p.product_id ORDER BY p.product_id LIMIT " . $start . ", " . $limit;
        $result = $this->db->query($sql);

        return $result->rows;
    }
}