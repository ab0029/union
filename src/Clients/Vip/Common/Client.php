<?php

namespace Young\Union\Clients\Vip\Common;

use Young\Union\Clients\Vip\Gateway;

class Client extends Gateway
{
    public function get(string $service, string $method, array $params = [], $requestAsync = false)
    {
        return $this->send($service, $method, $params, $requestAsync);
    }
}