<?php

namespace Young\Union\Clients\Pdd;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;

class Gateway extends Request
{
    const GATEWAY_URL = 'https://gw-api.pinduoduo.com/api/router';

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
            'version' => property_exists($this, 'version') ? $this->version : $this->app->getApiDefaultVersion(),
            'type' => $this->api_method,
            'date_type' => 'JSON',
            'timestamp' => (string) time(),
            'client_id' => $this->app->config->get('client_id'),
        ];
        $this->options['query'] = array_merge((array) $this->api_params, $data);
        if ( $access_token = $this['access_token'] ?? $this->app->config->get('access_token', '') ) {
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
        $str = $client_secret = $this->app->config->get('client_secret');
        foreach ($parameter as $key => $value) {
            if ( is_array($value) ) {
                $value = json_encode($value);
            } elseif ( is_bool($value) ) {
                $value = $value ? 'true' : 'false';
            }
            $str .= "$key$value";
        }
        $str = $str . $client_secret;

        $signature = strtoupper(md5($str));

        return $signature;
    }
}