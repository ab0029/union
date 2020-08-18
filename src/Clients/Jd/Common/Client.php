<?php

namespace Young\Union\Clients\Jd\Common;

use Young\Union\Clients\Jd\Gateway;

class Client extends Gateway
{
    public function get(string $method, array $params = [], $requestAsync = false)
    {
        return $this->send($method, $params, $requestAsync);
    }
}