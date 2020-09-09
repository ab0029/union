<?php

namespace Young\Union\Clients\Dataoke;

use Young\Union\Clients\ServiceContainer;

/**
 * 大淘客客户端
 * http://www.dataoke.com/pmc/openapi.html
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Common\ServiceProvider::class,
        Order\ServiceProvider::class,
    ];
}