<?php

namespace Young\Union\Clients\Taobao;

use Pimple\Container;
use Young\Union\Request\Request;
use Psr\Http\Message\ResponseInterface;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Gateway extends Request
{
    const GATEWAY_URL = 'http://gw.api.taobao.com/router/rest';

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
            'v' => property_exists($this, 'version') ? $this->version : $this->app->getApiDefaultVersion(),
            'app_key' => $this->app->config->get('app_key'),
            'method' => $this->api_method,
            'format' => 'json',
            'sign_method' => 'md5',
            'timestamp' => \date("Y-m-d H:i:s"),
            'session' => $this['session'] ?? $this->app->config->get('session', ''),
            'target_app_key' => $this->app->config->get('target_app_key', ''),
            'partner_id' => $this->app->config->get('partner_id', ''),
        ];
        $this->options['query'] = $data;
        $this->options['query']['sign'] = $this->signature();
        $this->options['form_params'] = (array) $this->api_params;
    }

    /**
     * 生成签名
     * @return string
     */
    protected function signature()
    {
        $parameter = array_merge((array) $this->api_params, $this->options['query']);
        ksort($parameter);
        $str = $app_secret = $this->app->config->get('app_secret');
        foreach ($parameter as $key => $value) {
            if (!is_array($value) && "@" != substr($value, 0, 1)) {
                $str .= "$key$value";
            }
        }
        $str = $str . $app_secret;

        $signature = strtoupper(md5($str));

        return $signature;
    }

    public function setSession($session)
    {
        $this['session'] = $session;
        return $this;
    }

    public function checkSessionOrThrow()
    {
        if(!isset($this['session']) && !$this->app->config->has('session') ) {
            throw new ClientException('session required', SDK::INVALID_ARGUMENT);
        }
    }
}