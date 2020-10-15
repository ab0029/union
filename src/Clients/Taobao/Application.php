<?php

namespace Young\Union\Clients\Taobao;

use Young\Union\Clients\ServiceContainer;

/**
 * 淘宝联盟客户端
 * https://open.alimama.com/#/document
 * https://tbk.bbs.taobao.com/detail.html?postId=8127005
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
        Order\ServiceProvider::class,
        Goods\ServiceProvider::class,
        Tool\ServiceProvider::class,
        OAuth\ServiceProvider::class,
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', '2.0');
    }
}