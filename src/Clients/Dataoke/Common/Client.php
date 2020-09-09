<?php

namespace Young\Union\Clients\Dataoke\Common;

use Young\Union\Clients\Dataoke\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    public function get(string $method, string $path, array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            throw new ClientException('version required', SDK::INVALID_ARGUMENT);
        }

        return $this->send($method, $path, $params, $requestAsync);
    }
}