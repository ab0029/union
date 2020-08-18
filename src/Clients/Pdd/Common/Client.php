<?php

namespace Young\Union\Clients\Pdd\Common;

use Young\Union\Clients\Pdd\Gateway;

class Client extends Gateway
{
    public function get(string $method, array $params = [], $requestAsync = false)
    {
        return $this->send($method, $params, $requestAsync);
    }
}