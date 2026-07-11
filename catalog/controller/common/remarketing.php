<?php

class ControllerCommonRemarketing extends Controller
{
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('tool/remarketing');
        $this->load->model('tool/remarketing_core');
    }

    public function header()
    {
        return $this->model_tool_remarketing->getRemarketingHeader();
    }

    public function body()
    {
        return $this->model_tool_remarketing->getRemarketingBody();
    }

    public function footer()
    {
        return $this->model_tool_remarketing->getRemarketingFooter();
    }

    public function sendGa4Mp()
    {
        $json = [];
        $response = $this->model_tool_remarketing_core->sendGa4();
        $json['success'] = true; // no return data
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sendFbCapi()
    {
        $json = [];
        $response = $this->model_tool_remarketing_core->sendFacebook();
        if (!empty($response['events_received'])) {
            $json['success'] = true;
        } elseif (!empty($response['error']['message'])) {
            $json['error'] = $response['error']['message'];
        } else {
            $json['error'] = 'Uncaught error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sendTg()
    {
        $json = [];
        $response = $this->model_tool_remarketing_core->sendTelegramMsg('test');
        if (!empty($response['ok'])) {
            $json['success'] = true;
        } elseif (empty($response['ok'])) {
            $json['error'] = $response['description'];
        } else {
            $json['error'] = 'Uncaught error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sendTikTokMapi()
    {
        $json = [];
        $response = $this->model_tool_remarketing_core->sendTiktok();
        if (isset($response['code']) && $response['code'] == 0) {
            $json['success'] = true;
        } elseif (isset($response['code']) && $response['code'] != 0) {
            $json['error'] = $response['message'];
        } else {
            $json['error'] = 'Uncaught error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sendEsputnikApi()
    {
        $json = [];
        $response = $this->model_tool_remarketing_core->sendEsputnik();
        if ($response) {
            $json['success'] = true;
        } else {
            $json['error'] = 'Uncaught error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function removeProduct()
    {
        if (isset($this->request->post)) {
            if (isset($this->request->post['product_id']) && isset($this->request->post['quantity'])) {
                if ($this->config->get('remarketing_status')) {
                    $this->load->model('catalog/product');
                    $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
                    $quantity = $this->request->post['quantity'];
                    $json = [];
                    $json['remarketing'] = [];
                    if ($product_info) {
                        $json['remarketing'] = $this->model_tool_remarketing->remarketingRemoveFromCart(
                            $product_info,
                            $quantity
                        );
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($json));
                    }
                }
            }
        }
    }
}