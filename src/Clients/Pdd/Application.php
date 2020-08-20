<?php

namespace Young\Union\Clients\Pdd;

use Young\Union\Clients\ServiceContainer;

/**
 * 拼多多客户端
 * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.cms.prom.url.generate
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
        return $this->config->get('version', 'V1');
    }
}