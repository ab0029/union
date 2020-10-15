<?php

namespace Young\Union\Clients\Taobao\OAuth;

use Pimple\Container;
use Young\Union\Clients\Taobao\Gateway;

class Client extends Gateway
{
    const BASE_URL = 'https://oauth.taobao.com';

    protected function resolveHost() 
    {
        $this->method = 'POST';
        $this->setUri('https://eco.taobao.com/router/rest');
    }

    protected $redirectUrl;

    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function redirect(string $state = null, string $view = 'wap')
    {
        $data = [
            'client_id' => $this->app['config']->get('app_key'),
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUrl ?? $this->app['config']->get('redirect_url', ''),
            'state' => $state ?? \Young\Union\uuid('taobao_oauth'),
            'view' => $view,
        ];

        return self::BASE_URL . '/authorize?' . http_build_query($data);
    }

    /**
     * 获取令牌
     */
    public function getAccessToken(string $code, $requestAsync = false)
    {
        return $this->send('taobao.top.auth.token.create', [
            'code' => $code,
        ], $requestAsync);
    }

    /**
     * 刷新令牌
     */
    public function refreshAccessToken(string $refresh_token, $requestAsync = false)
    {
        return $this->send('taobao.top.auth.token.refresh', [
            'refresh_token' => $refresh_token,
        ], $requestAsync);
    }
}
