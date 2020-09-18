<?php

namespace Young\Union\Clients\Kaola;

use Young\Union\Clients\ServiceContainer;

/**
 * 考拉客户端
 * https://pub.kaola.com/development
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
        return $this->config->get('version', '1.0');
    }
}