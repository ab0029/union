<?php

namespace Young\Union\Clients\Vip;

use Young\Union\Clients\ServiceContainer;

/**
 * 唯品会客户端
 * http://vop.vip.com/home#/service/list
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
        Order\ServiceProvider::class,
        Goods\ServiceProvider::class,
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', '1.0.0');
    }
}