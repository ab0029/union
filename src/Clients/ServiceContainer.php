<?php

namespace Young\Union\Clients;

use Young\Union\Config;
use Pimple\Container;

class ServiceContainer extends Container
{
    protected $providers = [];

    protected $userConfig = [];

    /**
     * Constructor.
     */
    public function __construct(array $config = [], array $prepends = [])
    {
        $this->userConfig = $config;

        $this->offsetSet('config', function($app) {
            return new Config($app->getUserConfig());
        });

        $this->registerProviders($this->getProviders());

        parent::__construct($prepends);
    }

    public function getUserConfig()
    {
        return $this->userConfig;
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            // ConfigServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @param string $id
     * @param mixed  $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
