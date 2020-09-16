<?php

namespace Young\Union\Clients\Dataoke;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;

class Gateway extends Request
{
    const GATEWAY_URL = 'https://openapi.dataoke.com';

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

    protected function send(string $method, string $path, array $params = [], $requestAsync = false)
    {
        $this->options['query'] = [];
        $this->options['body'] = '';
        $this->api_method = strtoupper($method);
        $this->api_path = $path;
        $this->api_params = array_merge($params, [
            'appKey' => $this->app->config->get('app_key'),
        ]);
        return $requestAsync ? $this->requestAsync()
                             : $this->request();
    }

    protected function resolveHost() 
    {
        $this->method = $this->api_method;
        if ( $this->isValidUrl($this->api_path) ) {
            $this->setUri($this->api_path);
        } else {
            $this->setUri(self::GATEWAY_URL . '/' . trim($this->api_path, '/'));
        }
    }

    protected function isValidUrl($path)
    {
        if (! preg_match('~^(//|https?://)~', $path)) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }

    protected function resolveParameter() 
    {
        $sign = $this->signature();
        $data = array_merge((array) $this->api_params, [
            'sign' => $sign
        ]);
        switch ($this->method) {
            case 'GET':
                $this->options['query'] = $data;
                break;
            case 'POST':
                $this->options['body'] = json_encode($data);
                break;
        }
    }

    /**
     * 生成签名
     * @return string
     */
    protected function signature()
    {
        $parameter = (array) $this->api_params;
        ksort($parameter);
        $str = '';
        foreach ($parameter as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        $str = trim($str, '&');
        $app_secret = $this->app->config->get('app_secret');
        $sign = strtoupper(md5($str . '&key=' . $app_secret));

        return $sign;
    }
}