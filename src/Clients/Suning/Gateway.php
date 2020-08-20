<?php

namespace Young\Union\Clients\Suning;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;

class Gateway extends Request
{
    const GATEWAY_URL = 'https://open.suning.com/api/http/sopRequest';

    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;

        parent::__construct(
            (array) $app['config']->get('http', [])
        );

        $this->setResponseResolver(function(ResponseInterface $response, Request $request) {
            return new Result($response, $request);
        });
    }

    protected function send(string $method, array $params = [], $requestAsync = false)
    {
        $this->options['query'] = [];
        $this->api_method = $method;
        $this->api_params = json_encode([
            'sn_request' =>[
                'sn_body' => !empty($params) ? $params : '',
            ]
        ]);
        $this->api_params_body = $params;
        return $requestAsync ? $this->requestAsync()
                             : $this->request();
    }

    protected function resolveHost() 
    {
        $this->method = 'POST';
        $this->resolveUrl($this->api_method);
    }

    private function resolveUrl(string $method)
    {
        $this->setUri(self::GATEWAY_URL . '/' . $method);
    }

    protected function resolveParameter() 
    {
        $sign = $this->signature();
        $this->options['headers']['AppMethod'] = $this->api_method;
        $this->options['headers']['AppRequestTime'] = $this->api_date;
        $this->options['headers']['Format'] = 'json';
        $this->options['headers']['signInfo'] = $sign;
        $this->options['headers']['AppKey'] = $this->app->config->get('app_key');
        $this->options['headers']['VersionNo'] = property_exists($this, 'version') ? $this->version : $this->app->getApiDefaultVersion();
        $this->options['headers']['Sdk-Version'] = 'suning-sdk-php-beta0.1';
        $this->options['headers']['Content-Type'] = 'text/json; charset=utf-8';
        if ( $access_token = $this['access_token'] ?? $this->app->config->get('access_token', '') ) {
            $this->options['headers']['access_token'] = $access_token;
        }
        $this->options['body'] = $this->api_params;
    }

    /**
     * 生成签名
     * @return string
     */
    protected function signature()
    {
        // 注意顺序要不要打乱
        // secret_key + method + date + app_key + api_version + post_field
        $data = [
            'secret_key' => $this->app->config->get('app_secret'),
            'method' => $this->api_method,
            'date' => date('Y-m-d H:i:s'),
            'app_key' => $this->app->config->get('app_key'),
            'api_version' => property_exists($this, 'version') ? $this->version : $this->app->getApiDefaultVersion(),
            'post_field' => base64_encode($this->api_params)
        ];

        $str = '';
        foreach($data as $k => $v){
            $str .= $v;
        }

        $signature = md5($str);
        $this->api_date = $data['date'];

        return $signature;
    }
}