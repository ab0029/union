<?php

namespace Young\Union\Clients\Taobao;

use Young\Union\Clients\ServiceContainer;

/**
 * 淘宝联盟客户端
 * https://open.alimama.com/#/document
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
        Order\ServiceProvider::class,
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', '2.0');
    }
}