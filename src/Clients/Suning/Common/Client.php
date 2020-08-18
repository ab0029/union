<?php

namespace Young\Union\Clients\Suning\Common;

use Young\Union\Clients\Suning\Gateway;

class Client extends Gateway
{
    public function get(string $method, array $params = [], $requestAsync = false)
    {
        return $this->send($method, $params, $requestAsync);
    }
}