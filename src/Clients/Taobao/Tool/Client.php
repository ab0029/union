<?php

namespace Young\Union\Clients\Taobao\Tool;

use Young\Union\Clients\Taobao\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        $params = array_merge([
            'adzone_id' => $this->app->config->get('adzone_id')
        ], $params);

        if (!isset($params['adzone_id'])) {
            throw new ClientException('adzone_id required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['material_id'])) {
            throw new ClientException('material_id required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.dg.optimus.material', $params, $requestAsync);
    }
}