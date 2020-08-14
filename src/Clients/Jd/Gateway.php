<?php

namespace Young\Union\Clients\Jd;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;

class Gateway extends Request
{
    const GATEWAY_URL = 'https://router.jd.com/api';

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

    protected function send(string $method, $params = [], $requestAsync = false)
    {
        $this->options['query'] = [];
        $this->api_method = $method;
        $this->param_json = is_array($params) ? json_encode($params) : $params;
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
        $this->options['query']['v'] = property_exists($this, 'v') ? $this->v : $this->app->getApiDefaultVersion();
        $this->options['query']['method'] = $this->api_method;
        $this->options['query']['app_key'] = $this->app->config->get('app_key');
        $this->options['query']['sign_method'] = 'md5';
        $this->options['query']['format'] = $this->format;
        $this->options['query']['timestamp'] = date('Y-m-d H:i:s', time());
        $this->options['query']['param_json'] = $this->param_json;
        if ( $access_token = $this->app->config->get('access_token', '') ) {
            $this->options['query']['access_token'] = $access_token;
        }
        $this->options['query']['sign'] = $this->signature();
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
            if ( !is_null($value) && $value !== '' ) {
                $str .= "$key$value";
            }
        }
        $str = $str . $app_secret;

        $signature = strtoupper(md5($str));

        return $signature;
    }
}