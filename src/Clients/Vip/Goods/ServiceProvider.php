<?php

namespace Young\Union\Clients\Vip\Goods;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['goods'] = function ($app) {
            return new Client($app);
        };
    }
}
