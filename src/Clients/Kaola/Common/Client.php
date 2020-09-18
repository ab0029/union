<?php

namespace Young\Union\Clients\Kaola\Common;

use Young\Union\Clients\Kaola\Gateway;

class Client extends Gateway
{
    public function get(string $method, array $params = [], $requestAsync = false)
    {
        return $this->send($method, $params, $requestAsync);
    }
}