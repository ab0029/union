<?php

namespace Young\Union\Clients\Jd;

use Young\Union\Clients\ServiceContainer;

/**
 * 京东客户端
 * https://union.jd.com/openplatform/api
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
        Order\ServiceProvider::class,
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', '1.0');
    }
}