<?php

namespace Young\Union\Clients\Jd;

use Young\Union\Clients\ServiceContainer;

class Application extends ServiceContainer
{
    protected $providers = [
        Order\ServiceProvider::class
    ];

    public function getApiDefaultVersion()
    {
        return $this->config->get('version', '1.0');
    }
}