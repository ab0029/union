<?php

namespace Young\Union\Clients\Pdd;

use Young\Union\Clients\ServiceContainer;

class Application extends ServiceContainer
{
    protected $providers = [
        Order\ServiceProvider::class
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', 'V1');
    }
}