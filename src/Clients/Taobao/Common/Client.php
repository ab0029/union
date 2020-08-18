<?php

namespace Young\Union\Clients\Taobao\Common;

use Young\Union\Clients\Taobao\Gateway;

class Client extends Gateway
{
    public function get(string $method, array $params = [], $requestAsync = false)
    {
        return $this->send($method, $params, $requestAsync);
    }
}