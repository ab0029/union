<?php

namespace Young\Union\Clients\Taobao\OAuth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['oauth'] = function ($app) {
            return new Client($app);
        };
    }
}
