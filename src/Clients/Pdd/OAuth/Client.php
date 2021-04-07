<?php

namespace Young\Union\Clients\Pdd\OAuth;

use Pimple\Container;
use Young\Union\Clients\Pdd\Gateway;

class Client extends Gateway
{
    const AUTH_URLS = [
        'seller' => 'https://mms.pinduoduo.com/open.html',
        'mobile' => 'https://mai.pinduoduo.com/h5-login.html',
        'ddk' => 'https://jinbao.pinduoduo.com/open.html',
    ];

    protected $redirectUrl;

    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function redirect(string $state = null, string $type = 'mobile')
    {
        $data = [
            'client_id' => $this->app['config']->get('app_key'),
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUrl ?? $this->app['config']->get('redirect_url', ''),
            'state' => $state ?? \Young\Union\uuid('taobao_oauth'),
        ];

        $url = self::AUTH_URLS[$type] ?? self::AUTH_URLS['mobile'];

        return $url . '?' . http_build_query($data);
    }

    /**
     * 获取令牌
     */
    public function getAccessToken(string $code, $requestAsync = false)
    {
        return $this->send('pdd.pop.auth.token.create', [
            'code' => $code,
        ], $requestAsync);
    }

    /**
     * 刷新令牌
     */
    public function refreshAccessToken(string $refresh_token, $requestAsync = false)
    {
        return $this->send('pdd.pop.auth.token.refresh', [
            'refresh_token' => $refresh_token,
        ], $requestAsync);
    }
}
