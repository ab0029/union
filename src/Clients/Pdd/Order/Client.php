<?php

namespace Young\Union\Clients\Pdd\Order;

use Young\Union\Clients\Pdd\Gateway;

class Client extends Gateway
{
    /**
     * 获取订单
     * 支持参数
     */
    public function list(array $params, $requestAsync = false)
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        if (!isset($params['page_size'])) {
            $params['page_size'] = 100;
        }
        if (!isset($params['start_update_time'])) {
            $params['start_update_time'] = strtotime(date('Y-m-d 00:00:00'));
        }
        if (!isset($params['end_update_time'])) {
            $params['end_update_time'] = time();
        }

        return $this->send('pdd.ddk.order.list.increment.get', $params, $requestAsync);
    }
}