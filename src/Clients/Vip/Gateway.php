<?php

namespace Young\Union\Clients\Vip;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;

class Gateway extends Request
{
    const GATEWAY_URL = 'https://gw.vipapis.com';

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

    protected function send(string $service, string $method, array $params = [], $requestAsync = false)
    {
        $this->options['query'] = [];
        $this->api_service = $service;
        $this->api_method = $method;
        $this->api_params = json_encode($params);
        return $requestAsync ? $this->requestAsync()
                             : $this->request();
    }

    protected function resolveHost() 
    {
        $this->method = 'POST';
        $this->setUri(self::GATEWAY_URL);
    }

    protected function resolveParameter() 
    {
        $data = [
            'service' => $this->api_service,
            'method' => $this->api_method,
            'version' => property_exists($this, 'version') ? $this->version : $this->app->getApiDefaultVersion(),
            'timestamp' => time(),
            'format' => 'json',
            'appKey' => $this->app->config->get('app_key'),
        ];
        $this->options['query'] = $data;
        if ( $access_token = $this->app->config->get('access_token', '') ) {
            $this->options['query']['accessToken'] = $access_token;
        }
        $this->options['query']['sign'] = $this->signature();
        $this->options['body'] = $this->api_params;
    }

    /**
     * 生成签名
     * @return string
     */
    protected function signature()
    {
        $parameter = $this->options['query'];
        ksort($parameter);
        $str = '';
        foreach ($parameter as $key => $value) {
            if ( is_array($value) ) {
                $value = json_encode($value);
            } elseif ( is_bool($value) ) {
                $value = $value ? 'true' : 'false';
            }
            $str .= "$key$value";
        }
        $str = $str . $this->api_params;

        $app_secret = $this->app->config->get('app_secret');

        if (function_exists('hash_hmac')) {
            return strtoupper(hash_hmac('md5', $str, $app_secret));
        }
    
        $app_secret = (strlen($app_secret) > 64) ? pack('H32', 'md5') : str_pad($app_secret, 64, chr(0));
        $ipad = substr($client_secret,0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($client_secret,0, 64) ^ str_repeat(chr(0x5C), 64);
        return strtoupper(md5($opad.pack('H32', md5($ipad.$str))));
    }
}