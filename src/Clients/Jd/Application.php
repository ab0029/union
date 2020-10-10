<?php

namespace Young\Union\Clients\Jd;

use Young\Union\Clients\ServiceContainer;

/**
 * 京东客户端
 * https://union.jd.com/openplatform/api
 * https://open.jd.com/home/home#/doc/api?apiCateId=461
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
        Order\ServiceProvider::class,
        Goods\ServiceProvider::class,
        Tool\ServiceProvider::class,
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', '1.0');
    }
}