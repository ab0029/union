<?php

namespace Young\Union\Clients\Kaola;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;

class Gateway extends Request
{
    const GATEWAY_URL = 'https://cps.kaola.com/zhuanke/api';

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
        $this->api_params = $params;
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
            'timestamp' => date('Y-m-d H:i:s'),
            'v' => property_exists($this, 'version') ? $this->version : $this->app->getApiDefaultVersion(),
            'signMethod' => 'md5',
            'unionId' => $this->app->config->get('app_key'),
            'method' => $this->api_method,
        ];
        $this->options['query'] = array_merge((array) $this->api_params, $data);
        $this->options['query']['sign'] = $this->signature();
        $this->options['headers']['Content-Type'] = 'text/json; charset=utf-8';
        $this->options['headers']['Accept'] = 'application/json; charset=utf-8';
    }

    /**
     * 生成签名
     * @return string
     */
    protected function signature()
    {
        $parameter = $this->options['query'];
        ksort($parameter);
        $str = $app_secret = $this->app->config->get('app_secret');
        foreach ($parameter as $key => $value) {
            $str .= "$key$value";
        }
        $str = $str . $app_secret;

        $signature = strtoupper(md5($str));

        return $signature;
    }
}