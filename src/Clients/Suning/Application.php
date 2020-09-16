<?php

namespace Young\Union\Clients\Suning;

use Young\Union\Clients\ServiceContainer;

/**
 * 舒宁客户端
 * https://open.suning.com/ospos/apipage/toApiMethodDetailMenuNew.do?bustypeId=3
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
        return $this->config->get('version', 'v1.2');
    }
}