<?php

namespace Young\Union\Clients\Taobao\Tool;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['tool'] = function ($app) {
            return new Client($app);
        };
    }
}
