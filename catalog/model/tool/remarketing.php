<?php

/*
 * WTFPL by CAPAXA https://ucrack.com
 */

class ModelToolRemarketing extends Model
{
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->ads_status = $this->config->get('remarketing_google_status') && $this->config->get(
                'remarketing_google_identifier'
            );
        $this->ga4_status = $this->config->get('remarketing_ga4_status');
        $this->ga4_dl_status = $this->config->get('remarketing_ga4_dl_status');
        $this->fb_status = $this->config->get('remarketing_facebook_status') && $this->config->get(
                'remarketing_facebook_identifier'
            );
        $this->snapchat_status = $this->config->get('remarketing_snapchat_status') && $this->config->get(
                'remarketing_snapchat_identifier'
            );
        $this->tiktok_status = $this->config->get('remarketing_tiktok_status') && $this->config->get(
                'remarketing_tiktok_identifier'
            );
        $this->esputnik_status = $this->config->get('remarketing_esputnik_status');
        $this->esputnik_webtracking_status = $this->esputnik_status && $this->config->get(
                'remarketing_esputnik_webtracking_status'
            ) && $this->config->get('remarketing_esputnik_webtracking_identifier');
        $this->ads_id = $this->config->get('remarketing_google_id');
        $this->ga4_id = $this->config->get('remarketing_ga4_id');
        $this->fb_id = $this->config->get('remarketing_facebook_id');
        $this->snapchat_id = $this->config->get('remarketing_snapchat_id');
        $this->tiktok_id = $this->config->get('remarketing_tiktok_id');
        $this->esputnik_id = $this->config->get('remarketing_esputnik_id');
        $this->ads_currency = $this->config->get('remarketing_google_currency');
        $this->ga4_currency = $this->config->get('remarketing_ga4_currency');
        $this->fb_currency = $this->config->get('remarketing_facebook_currency');
        $this->snapchat_currency = $this->config->get('remarketing_snapchat_currency');
        $this->tiktok_currency = $this->config->get('remarketing_tiktok_currency');
        $this->esputnik_currency = $this->config->get('remarketing_esputnik_currency');
        $this->is_bot = $this->isBot();
        $this->is_logged = $this->customer->isLogged();
        $this->current_url = 'https://' . (!empty($this->request->server['HTTP_HOST']) ? $this->request->server['HTTP_HOST'] : (!empty($this->request->server['SERVER_NAME']) ? $this->request->server['SERVER_NAME'] : 'localhost')) . (!empty($this->request->server['REQUEST_URI']) ? $this->request->server['REQUEST_URI'] : '/');
        $this->load->model('tool/remarketing_core');
        $this->model_tool_remarketing_core->getCid();
        $this->model_tool_remarketing_core->trackUtm();
    }

    public function getRemarketingHeader()
    {
        $output = '';

        if (!$this->is_bot) {
            $this->document->addScript('catalog/view/javascript/remarketing.js');
            if ($this->config->get('remarketing_counter1')) {
                $output .= $this->config->get('remarketing_counter1') . "\n";
            }

            if ($this->is_logged) {
                if ($this->ga4_status) {
                    $output .= "<script>if (typeof gtag != 'undefined') { gtag('set', 'user_id', '" . $this->is_logged . "')};</script>\n";
                }
                if ($this->ga4_dl_status) {
                    $output .= "<script>window.dataLayer = window.dataLayer || []; dataLayer.push({'GA4_user_id': '" . $this->is_logged . "'});</script>\n";
                }
                $email = $this->customer->getEmail();
                $firstname = $this->customer->getFirstName();
                $lastname = $this->customer->getLastName();
                $telephone = $this->customer->getTelephone();
            }

            if ($this->fb_status && $this->config->get('remarketing_facebook_script_status')) {
                $output .= "\n" . '<!-- Facebook Pixel Code -->' . "\n";
                $output .= '<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,\'script\',\'https://connect.facebook.net/en_US/fbevents.js\');';
                $parameters = [];
                if (!empty($email)) {
                    $parameters[] = "em:'" . $email . "'";
                    $parameters[] = "external_id:'" . $email . "'";
                }
                if (!empty($firstname)) {
                    $parameters[] = "fn:'" . $firstname . "'";
                }
                if (!empty($lastname)) {
                    $parameters[] = "ln:'" . $lastname . "'";
                }
                if (!empty($telephone)) {
                    $parameters[] = "ph:'" . $telephone . "'";
                }
                $output .= 'fbq(\'init\', \'' . $this->config->get(
                        'remarketing_facebook_identifier'
                    ) . '\',{' . implode(
                        ",",
                        $parameters
                    ) . '});fbq(\'track\', \'PageView\', {currency: \'' . $this->session->data['currency'] . '\', value: 0});';
                $output .= '</script><noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=' . $this->config->get(
                        'remarketing_facebook_identifier'
                    ) . '&ev=PageView&noscript=1"/></noscript>' . "\n";
                $output .= '<!-- End Facebook Pixel Code -->' . "\n";
            }

            if ($this->tiktok_status && $this->config->get('remarketing_tiktok_script_status')) {
                $output .= '<script>!function (w, d, t) {w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};ttq.load("' . $this->config->get(
                        'remarketing_tiktok_identifier'
                    ) . '");ttq.page()}(window, document, "ttq");</script>' . "\n";
                if ($this->is_logged) {
                    $output .= "<script>if (typeof ttq != 'undefined') {ttq.identify({sha256_email: '" . hash(
                            'sha256',
                            $email
                        ) . "',sha256_phone_number: '" . hash(
                            'sha256',
                            $this->phoneClear($telephone)
                        ) . "'})}</script>\n";
                }
            }

            if ($this->snapchat_status && $this->config->get('remarketing_snapchat_script_status')) {
                $output .= '<!-- Snap Pixel Code -->' . "\n";
                $output .= "<script>(function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function(){a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};a.queue=[];var s='script';r=t.createElement(s);r.async=!0;r.src=n;var u=t.getElementsByTagName(s)[0];u.parentNode.insertBefore(r,u);})(window,document,'https://sc-static.net/scevent.min.js');\n";
                $user_email = '';
                if ($this->is_logged) {
                    $user_email = "'user_email': '" . $email . "'";
                }
                $output .= "snaptr('init', '" . $this->config->get(
                        'remarketing_snapchat_identifier'
                    ) . "', {" . $user_email . "});snaptr('track', 'PAGE_VIEW');</script>\n";
                $output .= '<!-- End Snap Pixel Code -->' . "\n";
            }
        }

        if ($this->config->get('remarketing_counter_bot')) {
            $output .= "\n" . $this->config->get('remarketing_counter_bot');
        }
        if ($this->config->get('remarketing_debug_front_mode') && !empty($this->session->data['user_id'])) {
            $output .= '<style>.remarketing-debug {	background: #e4ffd6;padding: 15px;}</style>';
            $route = !empty($this->request->get['route']) ? $this->request->get['route'] : '';
            $output .= '<pre><div class="remarketing-debug" style=""><b>Remarketing Debug Head</b><br><br>route - ' . $route . '<br>' . htmlspecialchars(
                    $output
                ) . '</div></pre>';
        }
        $output = html_entity_decode($output, ENT_QUOTES, 'UTF-8');
        return $this->prepareOutput($output);
    }

    public function getRemarketingBody()
    {
        if ($this->is_bot) {
            return '';
        }
        $output = '';
        if ($this->config->get('remarketing_counter2') && $this->config->get('remarketing_counter2')) {
            $output .= "\n" . $this->config->get('remarketing_counter2');
        }
        $output = html_entity_decode($output, ENT_QUOTES, 'UTF-8');
        return $this->prepareOutput($output);
    }

    public function getRemarketingFooter()
    {
        $output = '';
        if ($this->is_bot) {
            return '';
        }

        $esputnik_general_info = '';

        if ($this->esputnik_webtracking_status) {
            $output .= "<script>!function (t, e, c, n) { var s = e.createElement(c); s.async = 1, s.src = 'https://statics.esputnik.com/scripts/' + n + '.js'; var r = e.scripts[0]; r.parentNode.insertBefore(s, r); var f = function () { f.c(arguments); }; f.q = []; f.c = function () { f.q.push(arguments); }; t['eS'] = t['eS'] || f; }(window, document, 'script', '" . $this->config->get(
                    'remarketing_esputnik_webtracking_identifier'
                ) . "'); </script><script>eS('init');</script>\n";
        }

        $route = !empty($this->request->get['route']) ? $this->request->get['route'] : '';

        $ads_ids = [];
        $fb_ids = [];
        $tiktok_ids = [];
        $snapchat_ids = [];
        $uet_ids = [];

        $fb_event_id = $tiktok_event_id = $this->genEventId();

        $begin_checkout_routes = [
            'checkout/cart',
            'checkout/simplecheckout',
            'checkout/unicheckout',
            'checkout/uni_checkout',
            'checkout/revcheckout',
            'revolution/revcheckout',
            'checkout/oct_fastorder',
            'checkout/fastorder',
            'checkout/onepcheckout',
            'checkout/checkout_two_step',
            'extension/module/buy',
            'checkout/buy',
            'extension/quickcheckout/checkout',
            'lightcheckout/checkout',
            'extension/module/custom',
            'tmdqc/tmdcheckout',
            'quickcheckout/checkout'
        ];

        if (in_array($route, $begin_checkout_routes)) {
            $route = 'checkout/checkout';
        }

        $success_page_routes = [
            'extension/ocdevwizard/smart_order_success_page_pro',
            'extension/ocdevwizard/order_success_page_pro',
            'extension/ocdevwizard/order_success_page',
            'oneclick/success'
        ];

        if (in_array($route, $success_page_routes)) {
            $route = 'checkout/success';
        }

        $custom_checkout_route = $this->config->get('remarketing_custom_checkout_route');
        if ($custom_checkout_route == $route) {
            $route = 'checkout/success';
        }

        $custom_begin_checkout_route = $this->config->get('remarketing_custom_begin_checkout_route');
        if ($custom_begin_checkout_route == $route) {
            $route = 'checkout/checkout';
        }

        switch ($route) {
            case '':
            case 'common/home':
                if ($this->esputnik_webtracking_status) {
                    $output .= $this->model_tool_remarketing_core->trackEvent('esputnik', 'MainPage');
                }
                if ($this->config->get('remarketing_uet_status')) {
                    $output .= "<script>window.uetq = window.uetq || []; window.uetq.push('event', '', {'ecomm_pagetype': 'home'});</script>\n\n";
                }
                break;
            case 'information/contact/success':
                if (!empty($this->session->data['remarketing_contact'])) {
                    if ($this->ga4_dl_status) {
                        $output .= "<script>window.dataLayer = window.dataLayer || []; dataLayer.push({'event': 'ga4_contact'})</script>\n";
                    }
                    if ($this->ga4_status) {
                        $output .= "<script>if (typeof gtag != 'undefined') {gtag('event', 'contact')}</script>\n";
                    }
                    if ($this->fb_status) {
                        if ($this->config->get('remarketing_facebook_pixel_status')) {
                            $output .= "<script>$(document).ready(function(){ if(typeof fbq != 'undefined'){ fbq('track', 'Contact', {eventID: '" . $fb_event_id . "'})}});</script>\n";
                        }
                        if ($this->config->get('remarketing_facebook_server_side')) {
                            $facebook_data['event_name'] = 'Contact';
                            $facebook_data['event_id'] = $fb_event_id;
                            $this->model_tool_remarketing_core->sendFacebook($facebook_data);
                        }
                    }
                    unset($this->session->data['remarketing_contact']);
                }
                break;
            case 'information/contact':
                if ($this->ga4_status) {
                    $output .= "<script>if (typeof gtag != 'undefined') {gtag('event', 'contact_page')}</script>\n";
                }
                if ($this->ga4_dl_status) {
                    $output .= "<script>window.dataLayer = window.dataLayer || []; dataLayer.push({'event': 'ga4_contact_page'})</script>\n";
                }
                break;
            case 'checkout/checkout':
                $ga4_products = [];

                if ($this->config->get('remarketing_events_cart')) {
                    $output .= "<script>\n";
                    $output .= html_entity_decode($this->config->get('remarketing_events_cart'));
                    $output .= "</script>\n";
                }

                $products = $this->cart->getProducts();
                $cart_json = [];

                foreach ($products as &$product) {
                    $product['category'] = $this->getRemarketingCategories($product['product_id']);
                    $ads_ids[] = $product[$this->ga4_id];
                    $fb_ids[] = $product;
                    $tiktok_ids[] = $product;
                    $snapchat_ids[] = $product[$this->snapchat_id];
                    $cart_json[$product['cart_id']] = $product;
                }

                if ($this->ga4_dl_status || $this->ga4_status || $this->config->get('remarketing_ga4_mp_status')) {
                    $ga4_event_name = $route != 'checkout/cart' ? 'begin_checkout' : 'view_cart';
                    $ga4_dl_event_name = 'ga4_' . $ga4_event_name;
                    $i = 1;
                    $this->load->model('catalog/product');
                    foreach ($products as $product) {
                        $product_info = $this->model_catalog_product->getProduct($product['product_id']);
                        $ga4_categories = $this->getRemarketingCategoriesGa4($product['product_id']);
                        $ga4_product = [
                            'item_id' => $product[$this->ga4_id],
                            'id' => $product[$this->ga4_id],
                            'google_business_vertical' => 'retail',
                            'item_name' => $this->getGa4Name($product['product_id'], addslashes($product['name'])),
                            'quantity' => (int)$product['quantity'],
                            'index' => $i,
                            'price' => $this->currency->format($product['price'], $this->ga4_currency, '', false),
                            'currency' => $this->ga4_currency
                        ];
                        if (!empty($ga4_categories[0])) {
                            $ga4_product['item_category'] = $ga4_categories[0];
                        }
                        if (!empty($ga4_categories[1])) {
                            $ga4_product['item_category2'] = $ga4_categories[1];
                        }
                        if (!empty($ga4_categories[2])) {
                            $ga4_product['item_category3'] = $ga4_categories[2];
                        }
                        if (!empty($ga4_categories[3])) {
                            $ga4_product['item_category4'] = $ga4_categories[3];
                        }
                        if (!empty($product_info['manufacturer'])) {
                            $ga4_product['item_brand'] = $product_info['manufacturer'];
                        }
                        $ga4_products[] = $ga4_product;
                        $i++;
                    }
                }

                $output .= "<script>\n";
                $output .= "window.cart_products = " . json_encode($cart_json, JSON_UNESCAPED_UNICODE) . "\n";
                $output .= "</script>\n";

                $cart_total = $this->cart->getTotal();
                $ads_total = $this->currency->format(
                    $cart_total * (float)$this->config->get('remarketing_google_ads_ratio'),
                    $this->ads_currency,
                    '',
                    false
                );
                $fb_total = $this->currency->format(
                    $cart_total * (float)$this->config->get('remarketing_facebook_ratio'),
                    $this->fb_currency,
                    '',
                    false
                );
                $ga4_total = $this->currency->format(
                    $cart_total * (float)$this->config->get('remarketing_ga4_ratio'),
                    $this->ga4_currency,
                    '',
                    false
                );
                $tiktok_total = $this->currency->format(
                    $cart_total * (float)$this->config->get('remarketing_tiktok_ratio'),
                    $this->tiktok_currency,
                    '',
                    false
                );
                $snapchat_total = $this->currency->format(
                    $cart_total * (float)$this->config->get('remarketing_snapchat_ratio'),
                    $this->snapchat_currency,
                    '',
                    false
                );

                if ($this->ga4_status) {
                    $ga4_event = [
                        'send_to' => $this->config->get('remarketing_ga4_identifier'),
                        'currency' => $this->ga4_currency,
                        'value' => $ga4_total,
                        'items' => $ga4_products
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent('ga4', $ga4_event_name, $ga4_event);
                }

                if ($this->ga4_dl_status) {
                    if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                        $ga4_dl_event_name = str_replace('ga4_', '', $ga4_dl_event_name);
                    }
                    $ga4_dl_event = [
                        'event' => $ga4_dl_event_name,
                        'ecommerce' => [
                            'currency' => $this->ga4_currency,
                            'value' => $ga4_total,
                            'items' => $ga4_products
                        ]
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'ga4_dl',
                        $ga4_dl_event_name,
                        $ga4_dl_event
                    );
                }

                if ($this->config->get('remarketing_ga4_mp_status')) {
                    $ga4_mp_event['ga4_data']['events'] = [
                        [
                            'name' => $ga4_event_name,
                            'params' => [
                                'currency' => $this->ga4_currency,
                                'items' => $ga4_products,
                                'value' => $ga4_total
                            ]
                        ]
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'ga4',
                        $ga4_event_name,
                        $ga4_mp_event,
                        '',
                        true
                    );
                }

                if ($this->ads_status && $this->config->get('remarketing_google_ads_identifier_cart_page')) {
                    $ads_conversion = [
                        'send_to' => $this->config->get('remarketing_google_ads_identifier_cart_page'),
                        'currency' => $this->ads_currency,
                        'value' => $ads_total
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent('ads', 'conversion', $ads_conversion);
                }

                if ($this->fb_status) {
                    $fb_event = [
                        'content_type' => 'product',
                        'num_items' => count($fb_ids),
                        'value' => $fb_total,
                        'currency' => $this->fb_currency
                    ];
                    $fb_event['contents'] = [];
                    $fb_event['content_ids'] = [];
                    foreach ($fb_ids as $product) {
                        $fb_event['contents'][] = [
                            'id' => $product[$this->fb_id],
                            'item_price' => $this->currency->format($product['price'], $this->fb_currency, '', false),
                            'quantity' => $product['quantity']
                        ];
                        $fb_event['content_ids'][] = $product[$this->fb_id];
                    }
                    if (count($fb_ids) == 1) {
                        $fb_event['content_name'] = $fb_ids[0]['name'];
                        $fb_event['content_category'] = $fb_ids[0]['category'];
                    }

                    if ($this->config->get('remarketing_facebook_pixel_status')) {
                        $output .= $this->model_tool_remarketing_core->trackEvent(
                            'fb',
                            'InitiateCheckout',
                            $fb_event,
                            $fb_event_id
                        );
                    }
                    if ($this->config->get('remarketing_facebook_server_side')) {
                        $capi_event = [
                            'event_name' => 'InitiateCheckout',
                            'custom_data' => [$fb_event],
                            'event_id' => $fb_event_id,
                            'url' => $this->current_url
                        ];
                        $output .= $this->model_tool_remarketing_core->trackEvent(
                            'fb',
                            'InitiateCheckout',
                            $capi_event,
                            $fb_event_id,
                            true
                        );
                    }
                }

                if ($this->tiktok_status) {
                    $tiktok_event = [
                        'content_type' => 'product',
                        'value' => $tiktok_total,
                        'currency' => $this->tiktok_currency,
                        'num_items' => count($tiktok_ids)
                    ];
                    $tiktok_event['contents'] = [];
                    $tiktok_event['content_ids'] = [];
                    foreach ($tiktok_ids as $product) {
                        $tiktok_event['contents'][] = [
                            'content_id' => $product[$this->tiktok_id],
                            'price' => $this->currency->format($product['price'], $this->tiktok_currency, '', false),
                            'content_name' => $product['name'],
                            'content_category' => $product['category'],
                            'quantity' => $product['quantity']
                        ];
                        $tiktok_event['content_ids'][] = $product[$this->tiktok_id];
                    }

                    if ($this->config->get('remarketing_tiktok_pixel_status')) {
                        $output .= $this->model_tool_remarketing_core->trackEvent(
                            'tiktok',
                            'InitiateCheckout',
                            $tiktok_event,
                            $tiktok_event_id
                        );
                    }

                    if ($this->config->get('remarketing_tiktok_server_side')) {
                        $mapi_event = [
                            'event_name' => 'InitiateCheckout',
                            'properties' => $tiktok_event,
                            'event_id' => $tiktok_event_id,
                            'url' => $this->current_url
                        ];
                        $output .= $this->model_tool_remarketing_core->trackEvent(
                            'tiktok',
                            'InitiateCheckout',
                            $mapi_event,
                            $tiktok_event_id,
                            true
                        );
                    }
                }

                if ($this->snapchat_status && $this->config->get('remarketing_snapchat_pixel_status')) {
                    $snapchat_event = [
                        'currency' => $this->snapchat_currency,
                        'item_ids' => $snapchat_ids,
                        'number_items' => count($snapchat_ids),
                        'price' => $snapchat_total
                    ];

                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'snapchat',
                        'START_CHECKOUT',
                        $snapchat_event
                    );
                }

                if ($this->config->get('remarketing_uet_status')) {
                    $uet_ids = [];
                    $uet_products = [];
                    foreach ($products as $product) {
                        $uet_ids[] = $product['product_id'];
                        $uet_products[] = [
                            'id' => $product[$this->ga4_id],
                            'quantity' => $product['quantity'],
                            'price' => $this->currency->format($product['price'], $this->ga4_currency, '', false)
                        ];
                    }
                    $uet_event = [
                        'ecomm_prodid' => $uet_ids,
                        'ecomm_pagetype' => 'cart',
                        'ecomm_totalvalue' => $ga4_total,
                        'revenue_value' => $ga4_total,
                        'currency' => $this->ga4_currency,
                        'items' => $uet_products
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent('uet', '', $uet_event);
                }

                break;
            case 'checkout/success':
                if (!empty($this->request->cookie['remarketing_order_id']) || !empty($this->session->data['order_id']) || !empty($this->session->data['remarketing_order_id'])) {
                    if (!empty($this->request->cookie['remarketing_order_id'])) {
                        $remarketing_order_id = $this->request->cookie['remarketing_order_id'];
                    }
                    if (!empty($this->session->data['order_id'])) {
                        $remarketing_order_id = $this->session->data['order_id'];
                    }
                    if (!empty($this->session->data['remarketing_order_id'])) {
                        $remarketing_order_id = $this->session->data['remarketing_order_id'];
                    }
                    $order_info = $this->model_tool_remarketing_core->getOrderRemarketing($remarketing_order_id);
                    if ($order_info) {
                        if (!empty($order_info['ec_data']) && ($this->ads_status || $this->ga4_status || $this->ga4_dl_status)) {
                            $output .= '<script>var enhanced_conversion_data = ' . json_encode(
                                    $order_info['ec_data'],
                                    JSON_UNESCAPED_UNICODE
                                ) . '</script>' . "\n";
                            $output .= "<script>if (typeof gtag != 'undefined') { gtag('set', 'user_data', " . json_encode(
                                    $order_info['ec_data'],
                                    JSON_UNESCAPED_UNICODE
                                ) . ");}</script>\n";
                        }

                        if ($order_info['reviews_event']) {
                            $output .= '<script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>' . "\n";
                            $output .= "<script>window.renderOptIn = function() { window.gapi.load('surveyoptin', function() { window.gapi.surveyoptin.render(" . json_encode(
                                    $order_info['reviews_event'],
                                    JSON_UNESCAPED_UNICODE
                                ) . ")})}</script>\n";
                        }

                        if ($this->ads_status) {
                            if ($order_info['ads_event']) {
                                $output .= $this->model_tool_remarketing_core->trackEvent(
                                    'ads',
                                    'purchase',
                                    $order_info['ads_event']
                                );
                            }

                            if ($order_info['ads_conversion']) {
                                $output .= $this->model_tool_remarketing_core->trackEvent(
                                    'ads',
                                    'conversion',
                                    $order_info['ads_conversion']
                                );
                            }
                        }

                        if ($this->ga4_status && !$this->config->get('remarketing_ga4_only_purchase')) {
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'ga4',
                                $order_info['ga4_event_name'],
                                $order_info['ga4_event']
                            );
                        }

                        if ($this->ga4_dl_status && !$this->config->get('remarketing_ga4_only_purchase')) {
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'dl',
                                'ec_data',
                                ['event' => 'ec_data', 'ec_data' => $order_info['ec_data']]
                            );
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'dl',
                                'client_data',
                                $order_info['client_data']
                            );
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'ga4_dl',
                                'ga4_dl_purchase',
                                $order_info['ga4_datalayer']
                            );
                        }

                        if ($this->fb_status && $this->config->get('remarketing_facebook_pixel_status')) {
                            if ($order_info['fb_event']) {
                                $output .= $this->model_tool_remarketing_core->trackEvent(
                                    'fb',
                                    'Purchase',
                                    $order_info['fb_event'],
                                    $order_info['sent_data']['fb_event_id']
                                );
                            }
                            if ($order_info['fb_lead_event']) {
                                $output .= $this->model_tool_remarketing_core->trackEvent(
                                    'fb',
                                    'Lead',
                                    $order_info['fb_lead_event'],
                                    $order_info['sent_data']['fb_lead_event_id']
                                );
                            }
                        }

                        if ($this->tiktok_status && $this->config->get('remarketing_tiktok_pixel_status')) {
                            if ($order_info['tiktok_event']) {
                                $output .= "<script>if (typeof ttq != 'undefined') {ttq.identify({email: '" . $order_info['email'] . "', phone_number: '" . $order_info['telephone'] . "'})}</script>\n";
                                $output .= $this->model_tool_remarketing_core->trackEvent(
                                    'tiktok',
                                    'Purchase',
                                    $order_info['tiktok_event'],
                                    $order_info['sent_data']['tt_event_id']
                                );
                            }
                        }

                        if ($this->snapchat_status && $this->config->get('remarketing_snapchat_pixel_status')) {
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'snapchat',
                                'PURCHASE',
                                $order_info['snapchat_event']
                            );
                        }

                        if ($this->config->get('remarketing_uet_status')) {
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'uet',
                                'purchase',
                                $order_info['uet_event']
                            );
                        }

                        if ($this->esputnik_webtracking_status) {
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'esputnik',
                                'CustomerData',
                                $order_info['es_cd_event']
                            );
                            $output .= $this->model_tool_remarketing_core->trackEvent(
                                'esputnik',
                                'PurchasedItems',
                                $order_info['esputnik_event']
                            );
                        }

                        $output .= "<script>remarketingLog('order_complete');</script>";
                    }

                    if ($this->config->get('remarketing_events_purchase')) {
                        $output .= "<script>\n";
                        $remarketing_events_purchase = html_entity_decode(
                            $this->config->get('remarketing_events_purchase')
                        );
                        $remarketing_events_purchase = str_replace(
                            ['{order_id}', '{order_total}', '{email}', '{telephone}'],
                            [
                                $order_info['order_id'],
                                $order_info['default_total'],
                                $order_info['email'],
                                $order_info['telephone']
                            ],
                            $remarketing_events_purchase
                        );
                        $output .= $remarketing_events_purchase;
                        $output .= "</script>\n";
                    }

                    $this->model_tool_remarketing_core->setSuccessPage($remarketing_order_id);
                }
                break;
            case 'error/not_found':
                if ($this->esputnik_webtracking_status) {
                    $output .= $this->model_tool_remarketing_core->trackEvent('esputnik', 'NotFound');
                }
                break;
            default:
                break;
        }

        if (!empty($this->session->data['remarketing_register']) && $this->is_logged) {
            if ($this->ga4_dl_status) {
                $output .= "<script>window.dataLayer = window.dataLayer || []; dataLayer.push({'event': 'ga4_registration'})</script>\n";
            }
            if ($this->ga4_status) {
                $output .= "<script>if (typeof gtag != 'undefined') {gtag('event', 'registration')}</script>\n";
            }
            if ($this->config->get('remarketing_ga4_mp_status')) {
                $ga4_mp_event['ga4_data']['events'] = [
                    [
                        'name' => 'registration',
                        'params' => [
                            'currency' => $this->ga4_currency
                        ]
                    ]
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'ga4',
                    'registration',
                    $ga4_mp_event,
                    '',
                    true
                );
            }

            if ($this->fb_status) {
                $fb_event = ['status' => true];
                if ($this->config->get('remarketing_facebook_pixel_status')) {
                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'fb',
                        'CompleteRegistration',
                        $fb_event,
                        $fb_event_id
                    );
                }
                if ($this->config->get('remarketing_facebook_server_side')) {
                    $capi_event = [
                        'event_name' => 'CompleteRegistration',
                        'custom_data' => [$fb_event],
                        'event_id' => $fb_event_id,
                        'url' => $this->current_url
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'fb',
                        'CompleteRegistration',
                        $capi_event,
                        $fb_event_id,
                        true
                    );
                }
            }

            if ($this->tiktok_status) {
                $tiktok_event = [
                    'currency' => $this->tiktok_currency
                ];

                if ($this->config->get('remarketing_tiktok_pixel_status')) {
                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'tiktok',
                        'CompleteRegistration',
                        $tiktok_event,
                        $tiktok_event_id
                    );
                }

                if ($this->config->get('remarketing_tiktok_server_side')) {
                    $mapi_event = [
                        'event_name' => 'CompleteRegistration',
                        'properties' => $tiktok_event,
                        'event_id' => $tiktok_event_id,
                        'url' => $this->current_url
                    ];
                    $output .= $this->model_tool_remarketing_core->trackEvent(
                        'tiktok',
                        'CompleteRegistration',
                        $mapi_event,
                        $tiktok_event_id,
                        true
                    );
                }
            }

            if ($this->esputnik_webtracking_status && $this->is_logged && !empty($this->session->data['esputnik_general_info'])) {
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'esputnik',
                    'CustomerData',
                    ['CustomerData' => $this->session->data['esputnik_general_info']]
                );
            }
            unset($this->session->data['remarketing_register']);
        }

        if (!empty($this->session->data['remarketing_login']) && $this->is_logged) {
            if ($this->ga4_dl_status) {
                $output .= "<script>window.dataLayer = window.dataLayer || []; dataLayer.push({'event': 'ga4_login'});</script>\n";
            }
            if ($this->ga4_status) {
                $output .= "<script>if (typeof gtag != 'undefined') {gtag('event', 'login')}</script>\n";
            }
            if ($this->config->get('remarketing_ga4_mp_status')) {
                $ga4_mp_event['ga4_data']['events'] = [
                    [
                        'name' => 'login',
                        'params' => [
                            'currency' => $this->ga4_currency
                        ]
                    ]
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent('ga4', 'login', $ga4_mp_event, '', true);
            }
            if ($this->esputnik_webtracking_status && $this->is_logged && !empty($this->session->data['esputnik_general_info'])) {
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'esputnik',
                    'CustomerData',
                    ['CustomerData' => $this->session->data['esputnik_general_info']]
                );
            }
            unset($this->session->data['remarketing_login']);
        }

        if ($this->config->get('remarketing_counter3') && $this->config->get('remarketing_counter3')) {
            $output .= html_entity_decode($this->config->get('remarketing_counter3'));
        }

        if ($this->config->get('remarketing_events_cart_add')) {
            $output .= "<script>\n";
            $output .= "function events_cart_add() {\n";
            $output .= html_entity_decode($this->config->get('remarketing_events_cart_add')) . "\n";
            $output .= "}\n";
            $output .= "</script>\n";
        }

        if ($this->config->get('remarketing_events_wishlist')) {
            $output .= "<script>\n";
            $output .= "function events_wishlist() {\n";
            $output .= html_entity_decode($this->config->get('remarketing_events_wishlist')) . "\n";
            $output .= "}\n";
            $output .= "</script>\n";
        }

        if ($this->config->get('remarketing_events_quick_purchase')) {
            $output .= "<script>\n";
            $output .= "function quickPurchase(order_id = false, order_total = false, email = false, telephone = false) {\n";
            $remarketing_events_quick_purchase = html_entity_decode(
                $this->config->get('remarketing_events_quick_purchase')
            );
            $remarketing_events_quick_purchase = str_replace(['{order_id}', '{order_total}', '{email}', '{telephone}'],
                ['order_id', 'order_total', 'email', 'telephone'],
                $remarketing_events_quick_purchase);
            $output .= html_entity_decode($remarketing_events_quick_purchase);
            $output .= "\n}\n";
            $output .= "</script>\n";
        }

        if ($this->fb_status && $this->config->get('remarketing_facebook_pixel_status') && $this->config->get(
                'remarketing_facebook_depth'
            ) && $this->config->get('remarketing_facebook_depth_params')) {
            $output .= '<script>' . "\n" . 'function getCurrentPosition() {' . "\n" . '	return window.pageYOffset ||' . "\n" . '		(document.documentElement || document.body.parentNode || document.body).scrollTop;' . "\n" . '}' . "\n" . '' . "\n" . 'function getScrollableHeight() {' . "\n" . '	var d = Math.max(' . "\n" . '		document.body.scrollHeight, document.documentElement.scrollHeight,' . "\n" . '		document.body.offsetHeight, document.documentElement.offsetHeight,' . "\n" . '		document.body.clientHeight, document.documentElement.clientHeight' . "\n" . '	)' . "\n" . '	var w = window.innerHeight ||' . "\n" . '		(document.documentElement || document.body).clientHeight;' . "\n" . '	if (d > w) {' . "\n" . '		return d - w;' . "\n" . '	}' . "\n" . '	return 0;' . "\n" . '}' . "\n" . 'var checkPoints = [' . $this->config->get(
                    'remarketing_facebook_depth_params'
                ) . '];' . "\n" . 'var reached = 0;' . "\n" . 'var scrollableHeight = getScrollableHeight();' . "\n" . "window.addEventListener('resize', function () {" . "\n" . '	scrollableHeight = getScrollableHeight();' . "\n" . '});' . "\n" . "window.addEventListener('scroll', function () {" . "\n" . '	var current;' . "\n" . '	if (scrollableHeight == 0) {' . "\n" . '		current = 100;' . "\n" . '	} else {' . "\n" . '		var current = getCurrentPosition() / scrollableHeight * 100;' . "\n" . '	}' . "\n" . '	if (current > reached) {' . "\n" . '		reached = current;' . "\n" . '		// checkpoint and send events' . "\n" . '		while (checkPoints.length > 0) {' . "\n" . '			var c = checkPoints[0];' . "\n" . '			if (c <= reached) {' . "\n" . '				checkPoints.shift();' . "\n" . "				if (typeof fbq != 'undefined') {" . "\n" . "					fbq('trackCustom', 'ViewContentCheckPoint', {" . "\n" . '						depth: c,' . "\n" . '					});' . "\n" . '				}' . "\n" . '			} else {' . "\n" . '				break;' . "\n" . '			}' . "\n" . '		}' . "\n" . '	}' . "\n" . '}, false);  ' . "\n" . '</script>' . "\n";
        }

        if ($this->config->get('remarketing_debug_front_mode') && !empty($this->session->data['user_id'])) {
            $output .= '<pre><div class="remarketing-debug" style=""><b>Remarketing Debug Footer</b><br><br>' . htmlspecialchars(
                    $output
                ) . '</div></pre>';
        }

        return $this->prepareOutput($output);
    }

    public function sendEsputnik($esputnik_data = [], $event_url = 'https://esputnik.com/api/v1/event')
    {
        $this->model_tool_remarketing_core->sendEsputnik($esputnik_data, $event_url);
    }

    public function sendEsputnikCartUpdated()
    {
        //deprecated
        return;
        $event = new stdClass();
        $event->eventTypeKey = 'cartUpdated';
        $event->keyValue = $this->session->data['esputnik_email'];
        $event->params = [];
        if (isset($this->session->data['esputnik_telephone'])) {
            $event->params[] = ['name' => 'phone', 'value' => $this->session->data['esputnik_telephone']];
        }
        $event->params[] = ['name' => 'email', 'value' => $this->session->data['esputnik_email']];

        $event->params[] = ['name' => 'currencyCode', 'value' => $this->esputnik_currency];

        if ($this->is_logged && !empty($this->session->data['esputnik_general_info']['externalCustomerId'])) {
            $event->params[] = [
                'name' => 'externalCustomerId',
                'value' => $this->session->data['esputnik_general_info']['externalCustomerId']
            ];
            if ($this->customer->getFirstName()) {
                $event->params[] = ['name' => 'firstName', 'value' => $this->customer->getFirstName()];
            }
            if ($this->customer->getLastName()) {
                $event->params[] = ['name' => 'lastName', 'value' => $this->customer->getLastName()];
            }
        }
        $items = [];

        $this->load->model('tool/image');
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            $items[] = [
                'productId' => (string)$product[$this->esputnik_id],
                'name' => $product['name'],
                'quantity' => (string)$product['quantity'],
                'price' => (string)$this->currency->format($product['price'], $this->esputnik_currency, '', false),
            ];
        }
        if (!isset($this->session->data['esputnik_uniq'])) {
            $this->session->data['esputnik_uniq'] = uniqid();
        }
        $event->params[] = ['name' => 'recycleStateId', 'value' => $this->session->data['esputnik_uniq']];
        $event->params[] = ['name' => 'products', 'value' => json_encode($items, JSON_UNESCAPED_UNICODE)];

        $this->sendEsputnik($event);
    }

    public function sendGa4($ecommerce_data, $order_info = [])
    {
        if (!$this->is_bot) {
            $this->model_tool_remarketing_core->sendGa4($ecommerce_data, $order_info);
        }
    }

    public function sendFacebook($facebook_data, $order_info = false, $return_data = false)
    {
        if (!$this->is_bot) {
            return $this->model_tool_remarketing_core->sendFacebook($facebook_data, $order_info, $return_data);
        }
    }

    public function sendTiktok($tiktok_data, $order_info = false)
    {
        if (!$this->is_bot) {
            $this->model_tool_remarketing_core->sendTiktok($tiktok_data, $order_info);
        }
    }

    public function sendTelegram($order_id)
    {
        $order_info = $this->getOrderRemarketing($order_id);
        if ($order_info) {
            $tg_message = $this->config->get('remarketing_telegram_message');

            $find = [
                '{order_id}',
                '{firstname}',
                '{lastname}',
                '{email}',
                '{telephone}',
                '{comment}',
                '{utm}',
                '{total}',
                '{shipping_method}',
                '{payment_method}',
                '{order_status}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            ];

            $replace = [
                'order_id' => $order_info['order_id'],
                'firstname' => $order_info['firstname'],
                'lastname' => $order_info['lastname'],
                'email' => $order_info['order_info']['email'],
                'telephone' => $order_info['order_info']['telephone'],
                'comment' => $order_info['order_info']['comment'],
                'utm' => str_replace('<br>', "\n", $this->getUtm($order_info['order_id'])),
                'total' => $order_info['default_total'],
                'shipping_method' => $order_info['order_info']['shipping_method'],
                'payment_method' => $order_info['order_info']['payment_method'],
                'order_status' => $order_info['order_info']['order_status'],
                'company' => $order_info['order_info']['shipping_company'],
                'address_1' => $order_info['order_info']['shipping_address_1'],
                'address_2' => $order_info['order_info']['shipping_address_2'],
                'city' => $order_info['order_info']['shipping_city'],
                'postcode' => $order_info['order_info']['shipping_postcode'],
                'zone' => $order_info['order_info']['shipping_zone'],
                'zone_code' => $order_info['order_info']['shipping_zone_code'],
                'country' => $order_info['order_info']['shipping_country']
            ];

            $tg_message = str_replace($find, $replace, $tg_message);
            $products = '';
            foreach ($order_info['products'] as $product) {
                if (!empty($product['variant'])) {
                    $product['name'] .= ' - ' . $product['variant'];
                }
                $products .= '<a href="' . $this->url->link(
                        'product/product',
                        'product_id=' . $product['product_id']
                    ) . '">' . $product['name'] . '</a> - ' . $product['price'] . ' х ' . $product['quantity'] . ' = ' . $product['total'] . "\n";
            }
            $tg_message = str_replace('{products}', $products, $tg_message);
            $tg_message = strip_tags($tg_message, '<a><b><i>');
            $tg_message = html_entity_decode($tg_message);
            $this->model_tool_remarketing_core->sendTelegramMsg($tg_message);
        }
    }

    public function remarketingAddToCart($product_info = [], $quantity = 1, $options = '', $option = [])
    {
        $json_data = [];
        if ($product_info) {
            $categories = $this->getRemarketingCategories($product_info['product_id']);
            $fb_event_id = $tt_event_id = $this->genEventId();
            $current_price = $product_info['special'] ? $product_info['special'] : $product_info['price'];
            if (!empty($option)) {
                $check_cart_price = $this->db->query(
                    "SELECT cart_id FROM " . DB_PREFIX . "cart WHERE product_id = '" . (int)$product_info['product_id'] . "' AND `option` = '" . $this->db->escape(
                        json_encode($option)
                    ) . "' ORDER BY cart_id DESC"
                );
                if ($check_cart_price->num_rows) {
                    $cart_id = $check_cart_price->row['cart_id'];
                    foreach ($this->cart->getProducts() as $product) {
                        if ($product['cart_id'] == $cart_id) {
                            $current_price = $product['price'];
                        }
                    }
                }
            }

            if ($this->ads_status) {
                $ads_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_google_ads_ratio'),
                    $this->ads_currency,
                    '',
                    false
                );
                $json_data['ads_event'] = [
                    'send_to' => $this->config->get('remarketing_google_identifier'),
                    'value' => $ads_price,
                    'items' => [
                        [
                            'id' => $product_info[$this->ads_id],
                            'price' => $ads_price,
                            'quantity' => $quantity,
                            'google_business_vertical' => 'retail'
                        ]
                    ],
                ];
                if ($this->config->get('remarketing_google_ads_identifier_cart')) {
                    $json_data['ads_conversion'] = [
                        'send_to' => $this->config->get('remarketing_google_ads_identifier_cart'),
                        'value' => $ads_price,
                        'currency' => $this->ads_currency
                    ];
                }
            }

            if ($this->snapchat_status) {
                $json_data['snapchat_event'] = [
                    'item_ids' => [$product_info[$this->snapchat_id]],
                    'item_category' => $categories,
                    'price' => $this->currency->format(
                        $current_price * (float)$this->config->get('remarketing_snapchat_ratio'),
                        $this->snapchat_currency,
                        '',
                        false
                    ),
                    'number_items' => $quantity,
                    'currency' => $this->snapchat_currency
                ];
            }
            if ($this->config->get('remarketing_uet_status')) {
                $uet_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_ga4_ratio'),
                    $this->ga4_currency,
                    '',
                    false
                );
                $json_data['uet_event'] = [
                    'ecomm_prodid' => [$product_info[$this->ga4_id]],
                    'ecomm_pagetype' => 'product',
                    'ecomm_totalvalue' => $uet_price * $quantity,
                    'revenue_value' => $uet_price * $quantity,
                    'currency' => $this->ga4_currency,
                    'items' => [
                        [
                            'id' => $product_info['product_id'],
                            'quantity' => $quantity,
                            'price' => $uet_price
                        ]
                    ]
                ];
            }

            if ($this->ga4_status || $this->ga4_dl_status || $this->config->get('remarketing_ga4_mp_status')) {
                $ga4_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_ga4_ratio'),
                    $this->ga4_currency,
                    '',
                    false
                );
                $ga4_categories = $this->getRemarketingCategoriesGa4($product_info['product_id']);
                $ga4_product = [
                    'item_name' => $this->getGa4Name($product_info['product_id'], $product_info['name']),
                    'item_id' => (string)$product_info[$this->ga4_id],
                    'id' => (string)$product_info[$this->ga4_id],
                    'google_business_vertical' => 'retail',
                    'index' => 1,
                    'price' => $ga4_price,
                    'currency' => $this->ga4_currency,
                    'quantity' => $quantity
                ];

                if (!empty($options)) {
                    $ga4_product['item_variant'] = $options;
                }
                if (!empty($product_info['manufacturer'])) {
                    $ga4_product['item_brand'] = $product_info['manufacturer'];
                }
                if (!empty($ga4_categories[0])) {
                    $ga4_product['item_category'] = $ga4_categories[0];
                }
                if (!empty($ga4_categories[1])) {
                    $ga4_product['item_category2'] = $ga4_categories[1];
                }
                if (!empty($ga4_categories[2])) {
                    $ga4_product['item_category3'] = $ga4_categories[2];
                }
                if (!empty($ga4_categories[3])) {
                    $ga4_product['item_category4'] = $ga4_categories[3];
                }
            }

            if ($this->ga4_status) {
                $json_data['ga4_event'] = [
                    'send_to' => $this->config->get('remarketing_ga4_identifier'),
                    'currency' => $this->ga4_currency,
                    'value' => $ga4_price * $quantity,
                    'items' => [$ga4_product]
                ];
            }
            if ($this->ga4_dl_status) {
                $json_data['ga4_datalayer'] = [
                    'event' => 'ga4_add_to_cart',
                    'ecommerce' => [
                        'currency' => $this->ga4_currency,
                        'value' => $ga4_price * $quantity,
                        'items' => [$ga4_product]
                    ]
                ];

                if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                    $json_data['ga4_datalayer']['event'] = 'add_to_cart';
                }

                if ($this->config->get('remarketing_ga4_dl_netpeak')) {
                    $json_data['ga4_datalayer']['value'] = $ga4_price * $quantity;
                    $json_data['ga4_datalayer']['items'] = [
                        [
                            'id' => $product_info[$this->ga4_id],
                            'google_business_vertical' => 'retail'
                        ]
                    ];
                }
            }
            if ($this->config->get('remarketing_ga4_mp_status')) {
                $ga4_data = [
                    'events' => [
                        [
                            'name' => 'add_to_cart',
                            'params' => [
                                'currency' => $this->ga4_currency,
                                'items' => [$ga4_product],
                                'value' => $ga4_price
                            ]
                        ],
                    ],
                ];
                $this->sendGa4($ga4_data);
            }

            if ($this->fb_status) {
                $fb_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_facebook_ratio'),
                    $this->fb_currency,
                    '',
                    false
                );
                $fb_event = [
                    'content_name' => $product_info['name'],
                    'content_ids' => $product_info[$this->fb_id],
                    'content_type' => 'product',
                    'contents' => [
                        [
                            'id' => $product_info[$this->fb_id],
                            'item_price' => $fb_price,
                            'content_name' => $product_info['name']
                        ]
                    ],
                    'content_category' => $categories,
                    'value' => $fb_price,
                    'currency' => $this->fb_currency
                ];
                if ($this->config->get('remarketing_facebook_pixel_status')) {
                    $json_data['fb_pixel_event'] = $fb_event;
                }

                if ($this->config->get('remarketing_facebook_server_side')) {
                    $facebook_data['event_name'] = 'AddToCart';
                    $facebook_data['custom_data'] = $fb_event;
                    $facebook_data['event_id'] = $fb_event_id;
                    $this->model_tool_remarketing_core->sendFacebook($facebook_data);
                }
            }

            if ($this->tiktok_status) {
                $tiktok_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_tiktok_ratio'),
                    $this->tiktok_currency,
                    '',
                    false
                );
                $tiktok_event = [
                    'content_name' => $product_info['name'],
                    'content_ids' => [$product_info[$this->tiktok_id]],
                    'contents' => [
                        [
                            'price' => $tiktok_price,
                            'quantity' => $quantity,
                            'content_id' => $product_info[$this->tiktok_id],
                            'content_name' => $product_info['name'],
                            'content_category' => $categories
                        ]
                    ],
                    'content_type' => 'product',
                    'value' => $tiktok_price * $quantity,
                    'price' => $tiktok_price,
                    'quantity' => $quantity,
                    'currency' => $this->tiktok_currency
                ];
                if ($this->config->get('remarketing_tiktok_pixel_status')) {
                    $json_data['tiktok_event'] = $tiktok_event;
                }

                if ($this->config->get('remarketing_tiktok_server_side')) {
                    $tiktok_data['event_name'] = 'AddToCart';
                    $tiktok_data['url'] = $this->current_url;
                    $tiktok_data['properties'] = $tiktok_event;
                    $tiktok_data['event_id'] = $tt_event_id;
                    $this->sendTiktok($tiktok_data);
                }
            }

            /*if (false && $this->esputnik_status && $this->is_logged && isset($this->session->data['esputnik_email'])) {
                $this->sendEsputnikCartUpdated();
                //deprecated
            }*/
            if ($this->esputnik_webtracking_status) {
                $this->session->data['remarketing_esputnik_cart_id'] = $this->genEventId();
                $cart_products = [];

                foreach ($this->cart->getProducts() as $cart_product) {
                    $cart_products[] = [
                        'productKey' => (string)$cart_product[$this->esputnik_id],
                        'price' => (string)$this->currency->format(
                            $cart_product['price'],
                            $this->esputnik_currency,
                            '',
                            false
                        ),
                        'quantity' => (string)$cart_product['quantity'],
                        'currency' => $this->esputnik_currency
                    ];
                }

                $json_data['esputnik_event'] = [
                    'StatusCart' => $cart_products,
                    'GUID' => $this->session->data['remarketing_esputnik_cart_id']
                ];

                if (!empty($this->session->data['esputnik_general_info'])) {
                    $json_data['esputnik_event']['GeneralInfo'] = $this->session->data['esputnik_general_info'];
                }
            }
        }

        return $json_data;
    }

    public function remarketingRemoveFromCart($product_info = [], $quantity = 1)
    {
        $json_data = [];
        if ($product_info) {
            $ga4_categories = $this->getRemarketingCategoriesGa4($product_info['product_id']);
            $current_price = $product_info['special'] ? $product_info['special'] : $product_info['price'];
            $ga4_price = $this->currency->format(
                $current_price * (float)$this->config->get('remarketing_ga4_ratio'),
                $this->ga4_currency,
                '',
                false
            );
            $variant = '';

            if (!empty($product_info['options']) && is_array($product_info['options'])) {
                foreach ($product_info['options'] as $option) {
                    $variant .= $option['name'] . ':' . $option['value'] . ';';
                }
                $variant = rtrim($variant, ';');
            }

            if ($this->ga4_status || $this->ga4_dl_status || $this->config->get('remarketing_ga4_mp_status')) {
                $ga4_product = [
                    'item_id' => $product_info[$this->ga4_id],
                    'item_name' => $this->getGa4Name($product_info['product_id'], $product_info['name']),
                    'index' => 1,
                    'price' => $ga4_price,
                    'quantity' => (int)$quantity
                ];
                if (!empty($product_info['manufacturer'])) {
                    $ga4_product['item_brand'] = $product_info['manufacturer'];
                }
                if (!empty($variant)) {
                    $ga4_product['item_variant'] = $variant;
                }
                if (!empty($ga4_categories[0])) {
                    $ga4_product['item_category'] = $ga4_categories[0];
                }
                if (!empty($ga4_categories[1])) {
                    $ga4_product['item_category2'] = $ga4_categories[1];
                }
                if (!empty($ga4_categories[2])) {
                    $ga4_product['item_category3'] = $ga4_categories[2];
                }
                if (!empty($ga4_categories[3])) {
                    $ga4_product['item_category4'] = $ga4_categories[3];
                }
            }

            if ($this->ga4_status) {
                $json_data['ga4_event'] = [
                    'send_to' => $this->config->get('remarketing_ga4_identifier'),
                    'currency' => $this->ga4_currency,
                    'value' => $ga4_price * $quantity,
                    'items' => [$ga4_product]
                ];
            }
            if ($this->ga4_dl_status) {
                $json_data['ga4_datalayer'] = [
                    'event' => 'ga4_remove_from_cart',
                    'value' => $ga4_price * $quantity,
                    'ecommerce' => [
                        'currency' => $this->ga4_currency,
                        'value' => $ga4_price * $quantity,
                        'items' => [$ga4_product]
                    ]
                ];

                if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                    $json_data['ga4_datalayer']['event'] = 'remove_from_cart';
                }
            }
            if ($this->config->get('remarketing_ga4_mp_status')) {
                $ga4_data = [
                    'events' => [
                        [
                            'name' => 'remove_from_cart',
                            'params' => [
                                'currency' => $this->ga4_currency,
                                'items' => [$ga4_product],
                                'value' => $ga4_price * $quantity
                            ]
                        ],
                    ],
                ];
                $this->sendGa4($ga4_data);
            }
            /*if (false && $this->esputnik_status && $this->is_logged && isset($this->session->data['esputnik_email'])) {
                $this->sendEsputnikCartUpdated();
                //deprecated
            }*/
            if ($this->esputnik_webtracking_status) {
                $this->session->data['remarketing_esputnik_cart_id'] = $this->genEventId();
                $cart_products = [];
                foreach ($this->cart->getProducts() as $cart_product) {
                    $cart_products[] = [
                        'productKey' => (string)$cart_product[$this->esputnik_id],
                        'price' => (string)$this->currency->format(
                            $cart_product['price'],
                            $this->esputnik_currency,
                            '',
                            false
                        ),
                        'quantity' => (string)$cart_product['quantity'],
                        'currency' => $this->esputnik_currency
                    ];
                }
                $json_data['esputnik_event'] = [
                    'StatusCart' => $cart_products,
                    'GUID' => $this->session->data['remarketing_esputnik_cart_id']
                ];

                if (!empty($this->session->data['esputnik_general_info'])) {
                    $json_data['esputnik_event']['GeneralInfo'] = $this->session->data['esputnik_general_info'];
                }
            }
        }
        return $json_data;
    }

    public function remarketingWishlist($product_info = [])
    {
        $json_data = [];
        if ($product_info) {
            $categories = $this->getRemarketingCategories($product_info['product_id']);
            $json_data = [];
            $current_price = $product_info['special'] ? $product_info['special'] : $product_info['price'];
            $quantity = 1;
            $fb_event_id = $tiktok_event_id = $this->genEventId();
            $json_data['event_id'] = $fb_event_id;
            if ($this->fb_status) {
                $fb_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_facebook_ratio'),
                    $this->fb_currency,
                    '',
                    false
                );
                $fb_event = [
                    'content_name' => $product_info['name'],
                    'content_ids' => [$product_info[$this->fb_id]],
                    'contents' => [
                        [
                            'id' => $product_info[$this->fb_id],
                            'item_price' => $fb_price,
                            'quantity' => 1
                        ]
                    ],
                    'content_type' => 'product',
                    'content_category' => $categories,
                    'num_items' => $quantity,
                    'value' => $fb_price,
                    'currency' => $this->fb_currency
                ];

                if ($this->config->get('remarketing_facebook_pixel_status')) {
                    $json_data['fb_pixel_event'] = $fb_event;
                }
                if ($this->config->get('remarketing_facebook_server_side')) {
                    $facebook_data['event_name'] = 'AddToWishlist';
                    $facebook_data['custom_data'] = $fb_event;
                    $facebook_data['event_id'] = $fb_event_id;
                    $this->model_tool_remarketing_core->sendFacebook($facebook_data);
                }
            }

            if ($this->tiktok_status) {
                $tiktok_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_tiktok_ratio'),
                    $this->tiktok_currency,
                    '',
                    false
                );
                $tiktok_event = [
                    'content_type' => 'product',
                    'currency' => $this->tiktok_currency,
                    'value' => $tiktok_price,
                    'num_items' => $quantity,
                    'content_ids' => [$product_info[$this->tiktok_id]],
                    'contents' => [
                        [
                            'content_id' => $product_info[$this->tiktok_id],
                            'price' => $tiktok_price,
                            'content_name' => $product_info['name'],
                            'brand' => $product_info['manufacturer'],
                            'content_category' => $categories
                        ]
                    ]
                ];

                if ($this->config->get('remarketing_tiktok_pixel_status')) {
                    $json_data['tiktok_event'] = $tiktok_event;
                }

                if ($this->config->get('remarketing_tiktok_server_side')) {
                    $tiktok_data['event_name'] = 'AddToWishlist';
                    $tiktok_data['url'] = $this->url->link(
                        'product/product',
                        'product_id=' . $product_info['product_id']
                    );
                    $tiktok_data['properties'] = $tiktok_event;
                    $tiktok_data['event_id'] = $json_data['event_id'];
                    $this->sendTiktok($tiktok_data);
                }
            }

            if ($this->snapchat_status) {
                $json_data['snapchat_event'] = [
                    'item_ids' => [$product_info[$this->snapchat_id]],
                    'item_category' => $categories,
                    'price' => $this->currency->format(
                        $current_price * (float)$this->config->get('remarketing_snapchat_ratio'),
                        $this->snapchat_currency,
                        '',
                        false
                    ),
                    'number_items' => $quantity,
                    'currency' => $this->snapchat_currency
                ];
            }
            if ($this->ga4_status || $this->ga4_dl_status || $this->config->get('remarketing_ga4_mp_status')) {
                $ga4_categories = $this->getRemarketingCategoriesGa4($product_info['product_id']);
                $ga4_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_ga4_ratio'),
                    $this->ga4_currency,
                    '',
                    false
                );

                $ga4_product = [
                    'item_id' => $product_info[$this->ga4_id],
                    'item_name' => $this->getGa4Name($product_info['product_id'], $product_info['name']),
                    'index' => 1,
                    'price' => $ga4_price,
                    'quantity' => $quantity
                ];
                if (!empty($brand)) {
                    $ga4_product['item_brand'] = $product_info['manufacturer'];
                }
                if (!empty($ga4_categories[0])) {
                    $ga4_product['item_category'] = $ga4_categories[0];
                }
                if (!empty($ga4_categories[1])) {
                    $ga4_product['item_category2'] = $ga4_categories[1];
                }
                if (!empty($ga4_categories[2])) {
                    $ga4_product['item_category3'] = $ga4_categories[2];
                }
                if (!empty($ga4_categories[3])) {
                    $ga4_product['item_category4'] = $ga4_categories[3];
                }
            }

            if ($this->ga4_status) {
                $json_data['ga4_event'] = [
                    'send_to' => $this->config->get('remarketing_ga4_identifier'),
                    'currency' => $this->ga4_currency,
                    'value' => $ga4_price,
                    'items' => [$ga4_product]
                ];
            }

            if ($this->ga4_dl_status) {
                $json_data['ga4_datalayer'] = [
                    'event' => 'ga4_add_to_wishlist',
                    'ecommerce' => [
                        'currency' => $this->ga4_currency,
                        'value' => $ga4_price,
                        'items' => [$ga4_product]
                    ]
                ];

                if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                    $json_data['ga4_datalayer']['event'] = 'add_to_wishlist';
                }
            }

            if ($this->config->get('remarketing_ga4_mp_status')) {
                $ga4_data = [
                    'events' => [
                        [
                            'name' => 'add_to_wishlist',
                            'params' => [
                                'currency' => $this->ga4_currency,
                                'items' => [$ga4_product],
                                'value' => $ga4_price
                            ]
                        ],
                    ],
                ];
                $this->sendGa4($ga4_data);
            }

            if ($this->esputnik_webtracking_status) {
                $json_data['esputnik_event'] = [
                    'AddToWishlist' => [
                        'productKey' => (string)$product_info[$this->esputnik_id],
                        'price' => (string)$this->currency->format($current_price, $this->esputnik_currency, '', false),
                        'isInStock' => ($product_info['quantity'] > 0 ? 1 : 0)
                    ]
                ];

                if (!empty($this->session->data['esputnik_general_info'])) {
                    $json_data['esputnik_event']['GeneralInfo'] = $this->session->data['esputnik_general_info'];
                }
            }
        }
        return $json_data;
    }

    public function remarketingCompare($product_info = [])
    {
        $json_data = [];
        if ($product_info) {
            $categories = $this->getRemarketingCategories($product_info['product_id']);
            $json_data = [];
            $current_price = $product_info['special'] ? $product_info['special'] : $product_info['price'];
            $quantity = 1;
            $fb_event_id = $tiktok_event_id = $this->genEventId();
            $json_data['event_id'] = $fb_event_id;
            if ($this->fb_status) {
                $fb_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_facebook_ratio'),
                    $this->fb_currency,
                    '',
                    false
                );
                $fb_event = [
                    'content_name' => $product_info['name'],
                    'content_ids' => [$product_info[$this->fb_id]],
                    'contents' => [
                        [
                            'id' => $product_info[$this->fb_id],
                            'item_price' => $fb_price,
                            'content_name' => $product_info['name']
                        ]
                    ],
                    'content_type' => 'product',
                    'content_category' => $categories,
                    'num_items' => $quantity,
                    'value' => $fb_price,
                    'currency' => $this->fb_currency
                ];

                if ($this->config->get('remarketing_facebook_pixel_status')) {
                    $json_data['fb_pixel_event'] = $fb_event;
                }
            }

            if ($this->ga4_status || $this->ga4_dl_status || $this->config->get('remarketing_ga4_mp_status')) {
                $ga4_categories = $this->getRemarketingCategoriesGa4($product_info['product_id']);
                $ga4_price = $this->currency->format(
                    $current_price * (float)$this->config->get('remarketing_ga4_ratio'),
                    $this->ga4_currency,
                    '',
                    false
                );

                $ga4_product = [
                    'item_id' => $product_info[$this->ga4_id],
                    'item_name' => $this->getGa4Name($product_info['product_id'], $product_info['name']),
                    'index' => 1,
                    'price' => $ga4_price,
                    'quantity' => $quantity
                ];
                if (!empty($brand)) {
                    $ga4_product['item_brand'] = $product_info['manufacturer'];
                }
                if (!empty($ga4_categories[0])) {
                    $ga4_product['item_category'] = $ga4_categories[0];
                }
                if (!empty($ga4_categories[1])) {
                    $ga4_product['item_category2'] = $ga4_categories[1];
                }
                if (!empty($ga4_categories[2])) {
                    $ga4_product['item_category3'] = $ga4_categories[2];
                }
                if (!empty($ga4_categories[3])) {
                    $ga4_product['item_category4'] = $ga4_categories[3];
                }
            }

            if ($this->ga4_status) {
                $json_data['ga4_event'] = [
                    'send_to' => $this->config->get('remarketing_ga4_identifier'),
                    'currency' => $this->ga4_currency,
                    'value' => $ga4_price,
                    'items' => [$ga4_product]
                ];
            }

            if ($this->ga4_dl_status) {
                $json_data['ga4_datalayer'] = [
                    'event' => 'ga4_add_to_compare',
                    'ecommerce' => [
                        'currency' => $this->ga4_currency,
                        'value' => $ga4_price,
                        'items' => [$ga4_product]
                    ]
                ];

                if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                    $json_data['ga4_datalayer']['event'] = 'add_to_compare';
                }
            }

            if ($this->config->get('remarketing_ga4_mp_status')) {
                $ga4_data = [
                    'events' => [
                        [
                            'name' => 'add_to_compare',
                            'params' => [
                                'currency' => $this->ga4_currency,
                                'items' => [$ga4_product],
                                'value' => $ga4_price
                            ]
                        ],
                    ],
                ];
                $this->sendGa4($ga4_data);
            }
        }
        return $json_data;
    }

    public function remarketingCallback()
    {
        $json_data = [];
        if ($this->ga4_status) {
            $json_data['ga4_event'] = [
                'send_to' => $this->config->get('remarketing_ga4_identifier')
            ];
        }
        if ($this->ga4_dl_status) {
            $json_data['ga4_datalayer'] = ['event' => 'ga4_callback'];
            if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                $json_data['ga4_datalayer']['event'] = 'callback';
            }
        }
        $json_data['fb_status'] = $this->fb_status && $this->config->get('remarketing_facebook_pixel_status');
        $json_data['tiktok_status'] = $this->tiktok_status && $this->config->get('remarketing_tiktok_pixel_status');
        return $json_data;
    }

    public function remarketingFoundCheaper()
    {
        $json_data = [];
        if ($this->ga4_status) {
            $json_data['ga4_event'] = [
                'send_to' => $this->config->get('remarketing_ga4_identifier')
            ];
        }
        if ($this->ga4_dl_status) {
            $json_data['ga4_datalayer'] = ['event' => 'ga4_found_cheaper'];
            if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                $json_data['ga4_datalayer']['event'] = 'found_cheaper';
            }
        }
        $json_data['fb_status'] = $this->fb_status && $this->config->get('remarketing_facebook_pixel_status');
        $json_data['tiktok_status'] = $this->tiktok_status && $this->config->get('remarketing_tiktok_pixel_status');
        return $json_data;
    }

    public function getQuickOrderOpen($product_info)
    {
        $json = [];
        if ($product_info && $this->config->get('remarketing_status') && !$this->is_bot) {
            $json['remarketing'] = $this->remarketingAddToCart($product_info, $quantity = 1);
        }
        return $json;
    }

    public function getQuickOrderSuccess($order_id, $send_history = false)
    {
        $json['remarketing'] = [];
        $json_data = [];
        $this->session->data['quick_order'] = true;
        $order_info = $this->getOrderRemarketing($order_id);
        if ($order_info) {
            $json['remarketing'] = $order_info;
            if (!$this->ga4_status || ($this->ga4_status && $this->config->get('remarketing_ga4_only_purchase'))) {
                unset($json['remarketing']['ga4_event']);
            }
            if (!$this->ga4_dl_status || ($this->ga4_dl_status && $this->config->get(
                        'remarketing_ga4_only_purchase'
                    ))) {
                unset($json['remarketing']['ga4_datalayer']);
            }
            if ($this->fb_status) {
                $json['remarketing']['fb_event_id'] = $order_info['sent_data']['fb_event_id'];
                $json['remarketing']['fb_lead_event_id'] = $order_info['sent_data']['fb_lead_event_id'];
            } else {
                unset($json['fb_event']);
            }
            if ($this->tiktok_status) {
                $json['remarketing']['tiktok_event_id'] = $order_info['sent_data']['tt_event_id'];
            } else {
                unset($json['tiktok_event']);
            }
        }
        if ($send_history) {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($order_id, $order_info['order_status_id'], 'quick_order');
        }
        return $json['remarketing'];
    }

    public function isBot()
    {
        if (!empty($this->session->data['process_order'])) {
            return false;
        }

        if (!empty($this->request->cookie['remarketing_bot'])) {
            return true;
        }

        if ($this->config->get(
                'remarketing_admin_status'
            ) && (!empty($this->session->data['user_id']) || !empty($this->session->data['api_id']))) {
            return true;
        }

        if ($this->config->get('remarketing_not_customer_groups') && in_array(
                $this->customer->getGroupId(),
                $this->config->get('remarketing_not_customer_groups')
            )) {
            return true;
        }

        if (!empty($this->request->server['HTTP_USER_AGENT']) && !$this->config->get('remarketing_bot_status')) {
            if (preg_match(
                '/360Spider|abacho|accona|AddThis|AdsBot|ahoy|AhrefsBot|AISearchBot|alexa|AlphaBot|altavista|Amazonbot|anthill|appie|Applebot|arale|araneo|AraybOt|ariadne|arks|aspseek|ATN_Worldwide|Atomz|baiduspider|baidu|Barkrowler|bbot|bingbot|bing|Bjaaland|BlackWidow|BitlyBot|BLEXBot|BotLink|bot|boxseabot|bspider|BuzzSumo|calif|CCBot|CensysInspect|ChinaClaw|christcrawler|CMC\/0\.01|Cocolyzebot|combine|confuzzledbot|contaxe|CoolBot|ClaudeBot|cosmos|crawler|crawlpaper|crawl|cusco|cyberspyder|cydralspider|DataForSeoBot|dataprovider|digger|DIIbot|DomainStatsBot|DotBot|downloadexpress|DragonBot|DuckDuckBot|dwcp|EasouSpider|ebiness|ecollector|elfinbot|esculapio|ESI|esther|eStyle|Exabot|Ezooms|facebookexternalhit|facebook|facebot|fastcrawler|FatBot|FDSE|FELIX IDE|fetch|fido|find|Firefly|fouineur|Freecrawl|froogle|gammaSpider|gazz|gcreep|geona|Getterrobo-Plus|girafabot|golem|Googlebot|\-google|grabber|GrabNet|GrapeshotCrawler|griffon|Gromit|gulliver|gulper|hambot|havIndex|HeadlessChrome|hotwired|htdig|HTTrack|ia_archiver|iajabot|IDBot|IndeedBot|Informant|InfoSeek|InfoSpiders|INGRID\/0\.01|inktomi|inspectorwww|Internet Cruiser Robot|irobot|Iron33|JBot|jcrawler|Jeeves|jobo|KDD\-Explorer|KIT\-Fireball|ko_yappo_robot|label\-grabber|larbin|legs|libwww-perl|linkedin|LinkedInBot|Linkidator|linkwalker|Lockon|logo_gif_crawler|LivelapBot|Lycos|m2e|Magpie-Crawler|majesticsEO|marvin|mattie|mediafox|Mediapartners-Google|mediapartners|MegaIndex|MerzScope|MetaInspector|MindCrawler|MJ12bot|mod_pagespeed|moget|MojeekBot|Monitority|Motor|msnbot|muncher|muninn|MuscatFerret|M Old|MuscatFerret|MwdSearch|NationalDirectory|naverbot|NEC\-MeshExplorer|Neevabot|NetcraftSurveyAgent|NetScoop|NetSeer|newscan\-online|nil|none|Nutch|ObjectsSearch|Occam|openstat\.ru\/Bot|packrat|pageboy|ParaSite|patric|pegasus|perlcrawler|PetalBot|phpdig|piltdownman|Pimptrain|pingdom|pinterest|pjspider|PlumtreeWebAccessor|PortalBSpider|psbot|Qwantify|rambler|Raven|RHCS|RixBot|roadrunner|Robbie|robi|RoboCrawl|robofox|RyteBot|Scooter|Scrubby|Search\-AU|SearchAtlas|searchprocess|search|Seekport|SeobilityBot|SemrushBot|Senrigan|SerendeputyBot|seznambot|SeznamBot|Shagseeker|sharp\-info\-agent|sift|SimBot|Site Valet|SiteSucker|skymob|SLCrawler\/2\.0|slurp|snooper|Sogou|solbot|speedy|spider_monkey|SpiderBot\/1\.0|spiderline|spider|suke|tach_bw|TechBOT|TechnoratiSnoop|templeton|Testomato|teoma|The Knowledge AI|titin|topiclink|TurnitinBot|twitterbot|twitter|UdmSearch|Ukonline|UnwindFetchor|URL_Spider_SQL|urlck|urlresolver|Vagabondo|Valkyrie libwww\-perl|verticrawl|Victoria|void\-bot|Voyager|VWbot_K|wapspider|WebBandit\/1\.0|webcatcher|WebCopier|WebFindBot|WebLeacher|WebMechanic|WebMoose|webquest|webreaper|webspider|webs|WebWalker|WebZip|whowhere|winona|wlm|WOLP|woriobot|WWWC|XGET|xing|yahoo|YandexBot|YandexMobileBot|yandex|yeti|YisouSpider|Zeus/i',
                $this->request->server['HTTP_USER_AGENT']
            )) {
                if (empty($this->request->cookie['remarketing_bot'])) {
                    setcookie('remarketing_bot', '1', time() + 3600 * 3, '/');
                }
                return true;
            }
        }
        return false;
    }

    public function genEventId()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function getUtm($order_id = false)
    {
        $utm_text = '';

        $utm_params = [
            'utm_source',
            'utm_campaign',
            'utm_term',
            'utm_medium',
            'utm_content',
            'utm_referrer',
            'first_referrer',
            'last_referrer'
        ];

        foreach ($utm_params as $utm_param) {
            if (!empty($this->session->data[$utm_param])) {
                $utm_text .= '' . $utm_param . ': ' . $this->session->data[$utm_param] . '<br>';
            }
        }

        if ($order_id) {
            $utm_text = '';
            $order_info = $this->getOrderRemarketing($order_id);
            foreach ($utm_params as $utm_param) {
                if (!empty($order_info['sent_data'][$utm_param])) {
                    $utm_text .= $utm_param . ': ' . $order_info['sent_data'][$utm_param] . '<br>';
                }
            }
        }

        $utm_text = rtrim($utm_text, '<br>');

        return $utm_text;
    }

    public function getRemarketingCategories($product_id)
    {
        $category_data = '';
        $category_query = $this->db->query(
            "SELECT DISTINCT cd.name FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "category_description` cd ON pc.category_id = cd.category_id LEFT JOIN `" . DB_PREFIX . "category_path` cp ON pc.category_id = cp.category_id WHERE pc.product_id = '" . (int)$product_id . "' AND cd.language_id = '" . (int)$this->config->get(
                'config_language_id'
            ) . "' ORDER BY cp.level ASC LIMIT 5"
        );
        foreach ($category_query->rows as $category) {
            $category_data .= $category['name'] . '/';
        }
        $category_data = rtrim($category_data, '/');
        return addslashes($category_data);
    }

    public function getRemarketingCategoriesGa4($product_id)
    {
        $category_data = [];
        $ocstore = false;
        if ($this->config->get('remarketing_ga4_seopro_categories')) {
            return $this->getRemarketingCategoriesGa4Ocstore($product_id);
        }
        $language_id = $this->config->get('config_language_id');
        if ($this->config->get('remarketing_ga4_language_id') && $this->config->get(
                'remarketing_ga4_language_id'
            ) != $this->config->get('config_language_id')) {
            $language_id = $this->config->get('remarketing_ga4_language_id');
        }
        $category_query = $this->db->query(
            "SELECT DISTINCT cd.name FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "category_description` cd ON pc.category_id = cd.category_id LEFT JOIN `" . DB_PREFIX . "category_path` cp ON pc.category_id = cp.category_id WHERE pc.product_id = '" . (int)$product_id . "' AND cd.language_id = '" . (int)$language_id . "' ORDER BY cp.level ASC LIMIT 5"
        );
        foreach ($category_query->rows as $category) {
            $category_data[] = $category['name'];
        }
        return $category_data;
    }

    public function getRemarketingCategoriesGa4Ocstore($product_id)
    {
        $category_data = [];
        $language_id = $this->config->get('config_language_id');
        if ($this->config->get('remarketing_ga4_language_id') && $this->config->get(
                'remarketing_ga4_language_id'
            ) != $this->config->get('config_language_id')) {
            $language_id = $this->config->get('remarketing_ga4_language_id');
        }
        $category_query = $this->db->query(
            "SELECT DISTINCT cd.name FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON pc.category_id = cp.category_id LEFT JOIN `" . DB_PREFIX . "category_description` cd ON cp.path_id = cd.category_id WHERE pc.product_id = '" . (int)$product_id . "' AND cd.language_id = '" . (int)$language_id . "'  AND pc.main_category = 1 ORDER BY cp.level ASC LIMIT 5"
        );
        foreach ($category_query->rows as $category) {
            $category_data[] = $category['name'];
        }
        return $category_data;
    }

    public function getGa4Name($product_id, $name)
    {
        if ($this->config->get('remarketing_ga4_language_id') && $this->config->get(
                'remarketing_ga4_language_id'
            ) != $this->config->get('config_language_id')) {
            $language_id = $this->config->get('remarketing_ga4_language_id');
            $name = $this->db->query(
                "SELECT `name` FROM `" . DB_PREFIX . "product_description` pd WHERE pd.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$language_id . "'"
            )->row['name'];
        }
        return $name;
    }

    public function makeRemarketingOrder(
        $name = '',
        $telephone = '',
        $email = '',
        $comment = '',
        $product_id = '',
        $quantity = 1,
        $order_status_id = 0
    ) {
        $json = [];
        if (empty($name) && empty($telephone) && empty($email)) {
            return 'No Data';
        }
        $order_data = [];
        $totals = [];
        $total = 0;
        $order_data['totals'] = [];
        $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $order_data['store_id'] = $this->config->get('config_store_id');
        $order_data['store_name'] = $this->config->get('config_name');
        if ($order_data['store_id']) {
            $order_data['store_url'] = $this->config->get('config_url');
        } else {
            $order_data['store_url'] = HTTPS_SERVER;
        }
        $order_firstname = '';
        $order_telephone = '';
        $order_comment = '';
        $order_email = 'empty@' . $this->request->server['SERVER_NAME'];

        if (!empty($name)) {
            $order_firstname = $name;
        }
        if (!empty($telephone)) {
            $order_telephone = $telephone;
        }
        if (!empty($email)) {
            $order_email = $email;
        }
        if (!empty($comment)) {
            $order_comment = $comment;
        }

        if ($this->is_logged) {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $order_data['customer_id'] = $this->customer->getId();
            $order_data['customer_group_id'] = $customer_info['customer_group_id'];
            $order_data['firstname'] = $order_firstname;
            $order_data['lastname'] = $customer_info['lastname'];
            $order_data['email'] = $order_email;
            $order_data['telephone'] = $order_telephone;
            $order_data['fax'] = '';
            $order_data['custom_field'] = json_decode($customer_info['custom_field'], true);
        } else {
            $order_data['customer_id'] = 0;
            $order_data['customer_group_id'] = 0;
            $order_data['firstname'] = $order_firstname;
            $order_data['lastname'] = '';
            $order_data['email'] = $order_email;
            $order_data['telephone'] = $order_telephone;
            $order_data['fax'] = '';
            $order_data['custom_field'] = [];
        }

        $order_data['payment_firstname'] = $order_firstname;
        $order_data['payment_lastname'] = '';
        $order_data['payment_company'] = '';
        $order_data['payment_address_1'] = '';
        $order_data['payment_address_2'] = '';
        $order_data['payment_city'] = '';
        $order_data['payment_postcode'] = '';
        $order_data['payment_zone'] = '';
        $order_data['payment_zone_id'] = '';
        $order_data['payment_country'] = '';
        $order_data['payment_country_id'] = '';
        $order_data['payment_address_format'] = '';
        $order_data['payment_custom_field'] = [];
        $order_data['payment_method'] = '';
        $order_data['payment_code'] = '';
        $order_data['shipping_firstname'] = $order_firstname;
        $order_data['shipping_lastname'] = '';
        $order_data['shipping_company'] = '';
        $order_data['shipping_address_1'] = '';
        $order_data['shipping_address_2'] = '';
        $order_data['shipping_city'] = '';
        $order_data['shipping_postcode'] = '';
        $order_data['shipping_zone'] = '';
        $order_data['shipping_zone_id'] = '';
        $order_data['shipping_country'] = '';
        $order_data['shipping_country_id'] = '';
        $order_data['shipping_address_format'] = '';
        $order_data['shipping_custom_field'] = [];
        $order_data['shipping_method'] = '';
        $order_data['shipping_code'] = '';

        $order_data['products'] = [];

        if (!empty($product_id)) {
            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product_id);
            if ($product_info) {
                $price = $product_info['special'] ? $product_info['special'] : $product_info['price'];

                $total = $price * $quantity;

                $option_data = [];

                $order_data['products'][] = [
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                    'model' => $product_info['model'],
                    'option' => $option_data,
                    'download' => '',
                    'quantity' => $quantity,
                    'subtract' => $product_info['subtract'],
                    'price' => $price * $quantity,
                    'total' => $price * $quantity,
                    'tax' => 0,
                    'reward' => $product_info['reward']
                ];
            }
        }

        $order_data['vouchers'] = [];
        $order_data['comment'] = $order_comment;
        $order_data['total'] = $total;

        $order_data['affiliate_id'] = 0;
        $order_data['commission'] = 0;
        $order_data['marketing_id'] = 0;
        $order_data['tracking'] = '';
        $order_data['language_id'] = $this->config->get('config_language_id');
        $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
        $order_data['currency_code'] = $this->session->data['currency'];
        $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
        $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (isset($this->request->server['HTTP_CLIENT_IP'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } elseif (isset($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($this->request->server['HTTP_CF_CONNECTING_IP'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_CF_CONNECTING_IP'];
        } else {
            $order_data['forwarded_ip'] = $this->request->server['REMOTE_ADDR'];
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $order_data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $order_data['accept_language'] = '';
        }

        $this->load->model('checkout/order');

        $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

        if ($order_status_id != 0) {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);
        }

        return $this->session->data['order_id'];
    }

    public function processCategory($category_info = [], $heading_title = '', $results = [])
    {
        if (empty($results) || $this->is_bot) {
            $data['remarketing_code'] = '';
            return $data;
        }
        $data = [];
        $output = '';
        $search_page = (!empty($this->request->get['route']) && $this->request->get['route'] == 'product/search' && !empty($this->request->get['search'])) ? true : false;
        $fb_event_id = $tiktok_event_id = $this->genEventId();

        if ($this->ads_status) {
            $ads_items = [];
            foreach ($results as $result) {
                $ads_items[] = [
                    'id' => $result[$this->ads_id],
                    'google_business_vertical' => 'retail'
                ];
            }
            $ads_event = [
                'send_to' => $this->config->get('remarketing_google_identifier'),
                'value' => 0,
                'items' => $ads_items
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent(
                'ads',
                !$search_page ? 'view_item_list' : 'view_search_results',
                $ads_event
            );
        }

        if ($this->ga4_status || $this->ga4_dl_status || $this->config->get('remarketing_ga4_mp_status')) {
            $ga4_products = [];
            $ads_products = [];
            $ga4_event_name = !$search_page ? 'view_item_list' : 'view_search_results';
            $i = 0;
            foreach ($results as $result) {
                $ga4_product = [
                    'item_name' => $this->getGa4Name($result['product_id'], $result['name']),
                    'item_id' => $result[$this->ga4_id],
                    'id' => $result[$this->ga4_id],
                    'google_business_vertical' => 'retail',
                    'index' => $i,
                    'price' => $this->currency->format(
                        $result['special'] ? $result['special'] : $result['price'],
                        $this->ga4_currency,
                        '',
                        false
                    ),
                    'quantity' => 1
                ];

                if (!empty($result['manufacturer'])) {
                    $ga4_product['item_brand'] = $result['manufacturer'];
                }
                $ga4_product['item_list_name'] = $heading_title;
                if (count($results) <= 20) {
                    $ga4_product['item_category'] = $ga4_product['item_list_name'];
                }
                $ga4_products[] = $ga4_product;
                $ads_products[] = [
                    'id' => $result[$this->ga4_id],
                    'google_business_vertical' => 'retail'
                ];
                $i++;
            }
        }

        if ($this->ga4_status) {
            $ga4_event = [
                'send_to' => $this->config->get('remarketing_ga4_identifier'),
                'currency' => $this->ga4_currency,
                'value' => 0,
                'items' => $ga4_products,
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('ga4', $ga4_event_name, $ga4_event);
        }

        if ($this->config->get('remarketing_ga4_mp_status')) {
            $ga4_mp_event['ga4_data']['events'] = [
                [
                    'name' => $ga4_event_name,
                    'params' => [
                        'currency' => $this->ga4_currency,
                        'items' => $ga4_products,
                        'value' => 0
                    ]
                ]
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('ga4', $ga4_event_name, $ga4_mp_event, '', true);
        }

        if ($this->ga4_dl_status) {
            $ga4_datalayer = [
                'event' => 'ga4_' . $ga4_event_name,
                'ecommerce' => [
                    'currency' => $this->ga4_currency,
                    'value' => 0,
                    'items' => [$ga4_product]
                ]
            ];

            if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                $ga4_datalayer['event'] = $ga4_event_name;
            }

            if ($this->config->get('remarketing_ga4_dl_netpeak')) {
                $ga4_datalayer['value'] = 0;
                $ga4_datalayer['currency'] = $this->ga4_currency;
                $ga4_datalayer['items'] = $ads_products;
            }
            $output .= $this->model_tool_remarketing_core->trackEvent('ga4_dl', $ga4_event_name, $ga4_datalayer);
        }

        if ($this->fb_status) {
            $fb_products = [];
            $fb_products_ids = [];
            $fb_event_name = !$search_page ? 'ViewContent' : 'Search';
            foreach ($results as $result) {
                $fb_products[] = [
                    'id' => $result[$this->fb_id],
                    'quantity' => 1,
                    'item_price' => $this->currency->format(
                        $result['special'] ? $result['special'] : $result['price'],
                        $this->fb_currency,
                        '',
                        false
                    )
                ];
                $fb_products_ids[] = $result[$this->fb_id];
            }
            $fb_event = [
                'content_type' => 'product',
                'content_ids' => $fb_products_ids,
                'contents' => $fb_products,
                'content_name' => $heading_title,
                'content_category' => !empty($category_info['name']) ? $category_info['name'] : '',
                'value' => 0,
                'currency' => $this->fb_currency
            ];

            if ($search_page) {
                $fb_event['search_string'] = $this->request->get['search'];
            }

            if ($this->config->get('remarketing_facebook_pixel_status')) {
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'fb',
                    $fb_event_name,
                    $fb_event,
                    $fb_event_id
                );
            }
            if ($this->config->get('remarketing_facebook_server_side')) {
                $capi_event = [
                    'event_name' => $fb_event_name,
                    'custom_data' => [$fb_event],
                    'event_id' => $fb_event_id,
                    'url' => $this->current_url
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'fb',
                    $fb_event_name,
                    $capi_event,
                    $fb_event_id,
                    true
                );
            }
        }

        if ($this->tiktok_status) {
            $tiktok_products = [];
            $tiktok_products_ids = [];
            $tiktok_event_name = !$search_page ? 'ViewContent' : 'Search';
            foreach ($results as $result) {
                $tiktok_products[] = [
                    'content_id' => $result[$this->tiktok_id],
                    'content_name' => $result['name'],
                    'quantity' => 1,
                    'price' => $this->currency->format(
                        $result['special'] ? $result['special'] : $result['price'],
                        $this->tiktok_currency,
                        '',
                        false
                    )
                ];
                $tiktok_products_ids[] = $result[$this->tiktok_id];
            }
            $tiktok_event = [
                'content_type' => 'product',
                'content_ids' => $tiktok_products_ids,
                'contents' => $tiktok_products,
                'content_name' => $heading_title,
                'value' => 0,
                'currency' => $this->tiktok_currency
            ];

            if ($search_page) {
                $tiktok_event['query'] = $this->request->get['search'];
            }

            if ($this->config->get('remarketing_tiktok_pixel_status')) {
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'tiktok',
                    $tiktok_event_name,
                    $tiktok_event,
                    $tiktok_event_id
                );
            }

            if ($this->config->get('remarketing_tiktok_server_side')) {
                $mapi_event = [
                    'event_name' => $tiktok_event_name,
                    'properties' => $tiktok_event,
                    'event_id' => $tiktok_event_id,
                    'url' => $this->current_url
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'tiktok',
                    $tiktok_event_name,
                    $mapi_event,
                    $tiktok_event_id,
                    true
                );
            }
        }

        /*if (0 && $this->config->get('remarketing_esputnik_status') && $this->is_logged) {
            $data['esputnik_remarketing_status'] = true;
            $data['esputnik_data_category_json'] = [
                'productCategoryId' => addslashes($heading_title)
            ];
        } */

        if ($this->esputnik_webtracking_status) {
            if (!empty($this->session->data['esputnik_general_info'])) {
                $esputnik_event['GeneralInfo'] = $this->session->data['esputnik_general_info'];
            }
            if (!$search_page) {
                $esputnik_event['CategoryPage'] = [
                    'categoryKey' => !empty($category_info['name']) ? $category_info['name'] : $heading_title
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent('esputnik', 'CategoryPage', $esputnik_event);
            } elseif (count($results) == 0) {
                $esputnik_event['SearchRequest'] = [
                    'search' => $this->request->get['search'],
                    'isFound' => count($results) > 0 ? 1 : 0
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent('esputnik', 'SearchRequest', $esputnik_event);
            }
        }

        if ($this->snapchat_status && $this->config->get('remarketing_snapchat_pixel_status')) {
            $snapchat_ids = [];
            $snapchat_brands = [];
            foreach ($results as $result) {
                $snapchat_ids[] = $result[$this->snapchat_id];
                $snapchat_brands[] = $result['manufacturer'];
            }

            $snapchat_event = [
                'currency' => $this->snapchat_currency,
                'item_ids' => $snapchat_ids,
                'number_items' => count($snapchat_ids),
                'item_category' => !empty($category_info['name']) ? $category_info['name'] : $heading_title
            ];
            if (!empty($snapchat_brands)) {
                $snapchat_event['brands'] = array_unique($snapchat_brands);
            }

            if ($search_page) {
                $snapchat_event['search_string'] = $this->request->get['search'];
            }

            $output .= $this->model_tool_remarketing_core->trackEvent(
                'snapchat',
                $search_page ? 'SEARCH' : 'VIEW_CONTENT',
                $snapchat_event
            );
        }

        if ($this->config->get('remarketing_uet_status')) {
            $uet_event = [
                'ecomm_pagetype' => 'product',
                'ecomm_prodid' => [$product_info['product_id']]
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('uet', '', $uet_event);
        }


        if ($this->config->get('remarketing_uet_status')) {
            $uet_ids = [];
            foreach ($results as $result) {
                $uet_ids[] = $result[$this->ga4_id];
            }
            $uet_data = [
                'ecomm_pagetype' => $search_page ? 'searchresults' : 'category',
                'ecomm_prodid' => $uet_ids
            ];

            if ($search_page) {
                $uet_data['ecomm_query'] = $this->request->get['search'];
            } else {
                $uet_data['ecomm_category'] = !empty($category_info['category_id']) ? $category_info['category_id'] : $heading_title;
            }
            $output .= $this->model_tool_remarketing_core->trackEvent('uet', '', $uet_event);
        }

        if (!empty($output)) {
            $data['remarketing_code'] = $this->prepareOutput($output);
        }

        if ($this->config->get('remarketing_debug_front_mode') && !empty($this->session->data['user_id'])) {
            $data['remarketing_code'] .= '<pre><div class="remarketing-debug" style=""><b>Remarketing Debug Product List</b><br><br>' . htmlspecialchars(
                    $data['remarketing_code']
                ) . '</div></pre>';
        }

        return $data;
    }

    public function processProduct($product_info = [], $category_info = [])
    {
        if (empty($product_info) || $this->is_bot) {
            return [];
        }
        $data = [];
        $output = '';
        $current_price = $product_info['special'] ? $product_info['special'] : $product_info['price'];

        $fb_event_id = $tiktok_event_id = $this->genEventId();

        if ($this->ga4_status || $this->ga4_dl_status || $this->config->get('remarketing_ga4_mp_status')) {
            $ga4_price = $this->currency->format($current_price, $this->ga4_currency, '', false);
            $ga4_categories = $this->getRemarketingCategoriesGa4($product_info['product_id']);
            $ga4_product = [
                'item_id' => (string)$product_info[$this->ga4_id],
                'id' => (string)$product_info[$this->ga4_id],
                'google_business_vertical' => 'retail',
                'item_name' => $this->getGa4Name($product_info['product_id'], $product_info['name']),
                'index' => 1,
                'price' => $ga4_price,
                'quantity' => 1
            ];
            if (!empty($product_info['manufacturer'])) {
                $ga4_product['item_brand'] = $product_info['manufacturer'];
            }
            $ga4_product['item_list_name'] = !empty($category_info['name']) ? $category_info['name'] : '';
            if (!empty($ga4_categories[0])) {
                $ga4_product['item_category'] = $ga4_categories[0];
            }
            if (!empty($ga4_categories[1])) {
                $ga4_product['item_category2'] = $ga4_categories[1];
            }
            if (!empty($ga4_categories[2])) {
                $ga4_product['item_category3'] = $ga4_categories[2];
            }
            if (!empty($ga4_categories[3])) {
                $ga4_product['item_category4'] = $ga4_categories[3];
            }
        }

        if ($this->ga4_status) {
            $ga4_event = [
                'send_to' => $this->config->get('remarketing_ga4_identifier'),
                'currency' => $this->ga4_currency,
                'value' => $ga4_price,
                'items' => [$ga4_product],
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent(
                'ga4',
                'view_item',
                $ga4_event,
                '',
                false,
                ['add_click' => true]
            );
        }

        if ($this->config->get('remarketing_ga4_mp_status')) {
            $ga4_mp_event['ga4_data']['events'] = [
                [
                    'name' => 'view_item',
                    'params' => [
                        'currency' => $this->ga4_currency,
                        'items' => [$ga4_product],
                        'value' => $ga4_price
                    ]
                ]
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('ga4', 'view_item', $ga4_mp_event, '', true);
        }

        if ($this->ga4_dl_status) {
            $ga4_datalayer = [
                'event' => 'ga4_view_item',
                'ecommerce' => [
                    'currency' => $this->ga4_currency,
                    'value' => $ga4_price,
                    'items' => [$ga4_product]
                ]
            ];

            $ga4_click_datalayer = [
                'event' => 'ga4_select_item',
                'ecommerce' => [
                    'currency' => $this->ga4_currency,
                    'value' => $ga4_price,
                    'items' => [$ga4_product]
                ]
            ];

            if ($this->config->get('remarketing_ga4_dl_remove_prefix')) {
                $ga4_datalayer['event'] = 'view_item';
                $ga4_click_datalayer['event'] = 'select_item';
            }

            if ($this->config->get('remarketing_ga4_dl_netpeak')) {
                $ga4_datalayer['value'] = $ga4_price;
                $ga4_datalayer['currency'] = $this->ga4_currency;
                $ga4_datalayer['items'] = [
                    [
                        'id' => $product_info[$this->ga4_id],
                        'google_business_vertical' => 'retail'
                    ]
                ];
            }
            $output .= $this->model_tool_remarketing_core->trackEvent(
                'ga4_dl',
                'view_item',
                $ga4_datalayer,
                '',
                false,
                ['add_click' => $ga4_click_datalayer]
            );
        }

        if ($this->ads_status) {
            $ads_event = [
                'send_to' => $this->config->get('remarketing_google_identifier'),
                'value' => $this->currency->format($current_price, $this->ads_currency, '', false),
                'items' => [['id' => $product_info[$this->ads_id], 'google_business_vertical' => 'retail']]
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('ads', 'view_item', $ads_event);
        }

        if ($this->fb_status) {
            $fb_price = $this->currency->format($current_price, $this->fb_currency, '', false);
            $fb_event = [
                'content_type' => 'product',
                'content_ids' => [$product_info[$this->fb_id]],
                'contents' => [['id' => $product_info[$this->fb_id], 'quantity' => 1, 'item_price' => $fb_price]],
                'content_name' => $product_info['name'],
                'content_category' => !empty($category_info['name']) ? $category_info['name'] : '',
                'value' => $fb_price,
                'currency' => $this->fb_currency
            ];
            if ($this->config->get('remarketing_facebook_pixel_status')) {
                $output .= $this->model_tool_remarketing_core->trackEvent('fb', 'ViewContent', $fb_event, $fb_event_id);
            }
            if ($this->config->get('remarketing_facebook_server_side')) {
                $capi_event = [
                    'event_name' => 'ViewContent',
                    'custom_data' => [$fb_event],
                    'event_id' => $fb_event_id,
                    'url' => $this->current_url
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'fb',
                    'ViewContent',
                    $capi_event,
                    $fb_event_id,
                    true
                );
            }
        }

        if ($this->tiktok_status) {
            $tiktok_price = $this->currency->format($current_price, $this->tiktok_currency, '', false);
            $tiktok_event = [
                'content_type' => 'product',
                'content_ids' => [$product_info[$this->tiktok_id]],
                'content_name' => $product_info['name'],
                'contents' => [
                    [
                        'content_id' => $product_info[$this->tiktok_id],
                        'content_name' => $product_info['name'],
                        'quantity' => 1,
                        'price' => $tiktok_price
                    ]
                ],
                'content_category' => !empty($category_info['name']) ? $category_info['name'] : '',
                'value' => $tiktok_price,
                'price' => $tiktok_price,
                'currency' => $this->tiktok_currency
            ];
            if ($this->config->get('remarketing_tiktok_pixel_status')) {
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'tiktok',
                    'ViewContent',
                    $tiktok_event,
                    $tiktok_event_id
                );
            }

            if ($this->config->get('remarketing_tiktok_server_side')) {
                $mapi_event = [
                    'event_name' => 'ViewContent',
                    'properties' => $tiktok_event,
                    'event_id' => $tiktok_event_id,
                    'url' => $this->current_url
                ];
                $output .= $this->model_tool_remarketing_core->trackEvent(
                    'tiktok',
                    'ViewContent',
                    $mapi_event,
                    $tiktok_event_id,
                    true
                );
            }
        }
        /*
        if ($this->esputnik_status && $this->is_logged && $this->config->get('remarketing_esputnik_api_status')) {
            $esputnik_event['event_name'] = 'productViewed';
            $esputnik_event['event_type'] = 'product';
            $esputnik_event['event_data'] = [
                'productId' => $product_info['name'],
                'quantity'  => $product_info['quantity'],
                'price'     => $this->currency->format($current_price, $this->esputnik_currency, '', false),
                'isInStock' => $product_info['quantity'] > 0 ? 1 : 0
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('esputnik', 'productViewed', $esputnik_event, '', true);
        }
        */
        if ($this->esputnik_webtracking_status) {
            $esputnik_event['ProductPage'] = [
                'productKey' => (string)$product_info[$this->esputnik_id],
                'price' => (string)$this->currency->format($current_price, $this->esputnik_currency, '', false),
                'isInStock' => $product_info['quantity'] > 0 ? 1 : 0
            ];
            if (!empty($this->session->data['esputnik_general_info'])) {
                $esputnik_event['GeneralInfo'] = $this->session->data['esputnik_general_info'];
            }
            $output .= $this->model_tool_remarketing_core->trackEvent('esputnik', 'ProductPage', $esputnik_event);
        }

        if ($this->snapchat_status && $this->config->get('remarketing_snapchat_pixel_status')) {
            $snapchat_event = [
                'currency' => $this->snapchat_currency,
                'item_ids' => [$product_info[$this->snapchat_id]],
                'number_items' => '1',
                'item_category' => !empty($category_info['name']) ? $category_info['name'] : $product_info['name'],
                'price' => $this->currency->format($current_price, $this->snapchat_currency, '', false)
            ];
            if ($product_info['manufacturer']) {
                $snapchat_event['brands'][0] = $product_info['manufacturer'];
            }
            $output .= $this->model_tool_remarketing_core->trackEvent('snapchat', 'VIEW_CONTENT', $snapchat_event);
        }

        if ($this->config->get('remarketing_uet_status')) {
            $uet_event = [
                'ecomm_pagetype' => 'product',
                'ecomm_prodid' => [$product_info['product_id']]
            ];
            $output .= $this->model_tool_remarketing_core->trackEvent('uet', '', $uet_event);
        }

        if (!empty($output)) {
            $data['remarketing_code'] = $this->prepareOutput($output);
        }

        if ($this->config->get('remarketing_debug_front_mode') && !empty($this->session->data['user_id'])) {
            $data['remarketing_code'] .= '<pre><div class="remarketing-debug" style=""><b>Remarketing Debug Product</b><br><br>' . htmlspecialchars(
                    $data['remarketing_code']
                ) . '</div></pre>';
        }

        return $data;
    }

    public function phoneClear($telephone, $delete_plus = false)
    {
        $telephone = preg_replace('/[^0-9+]/', '', $telephone);
        if ($delete_plus) {
            $telephone = str_replace('+', '', $telephone);
        }
        return $telephone;
    }

    private function prepareOutput($output)
    {
        return str_replace('<script', '<script data-module="remarketing"', $output);
    }

    public function getOrderRemarketing($order_id)
    {
        $modification = [];
        $remarketing_data = $this->model_tool_remarketing_core->getOrderRemarketing($order_id, $modification);
        return $remarketing_data;
    }
}