<?php

namespace Young\Union\Clients\Suning\Order;

use Young\Union\Clients\Suning\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 获取订单
     * 支持参数
     * pageNo   String  Y   1   页码。默认值：1。页码和每页条数成对出现
     * pageSize    String  Y   10  每页条数。最大值：50，默认值：10，页码和每页条数成对出现
     * startTime   String  Y   2017-10-10 10:00:00 查询开始时间。格式:yyyy-MM-dd HH:mm:ss，若输入则开始时间和结束时间必须同时出现，开始时间和结束时间间隔不大于1天
     * endTime String  Y   2017-10-10 10:00:00 查询结束时间。格式:yyyy-MM-dd HH:mm:ss，若输入则开始时间和结束时间必须同时出现，开始时间和结束时间间隔不大于1天
     * orderLineStatus String  N   1   订单行项目状态（0：全部状态；2：支付完成；3：退款；5：确认收货）
     * promotion   String  N   1   1.风控订单
     * pid String  N   qqwwee112233    工具商pid
     * orderChannel    String  N   14905   订单渠道号
     */
    public function list(array $params = [], $requestAsync = false)
    {
        if (!isset($params['pageNo'])) {
            $params['pageNo'] = 1;
        }

        if (!isset($params['pageSize'])) {
            throw new ClientException('pageSize required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['startTime'])) {
            throw new ClientException('startTime required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['endTime'])) {
            throw new ClientException('endTime required', SDK::INVALID_ARGUMENT);
        }

        $data = [
            'queryOrder' => $params,
        ];

        return $this->send('suning.netalliance.order.query', $data, $requestAsync);
    }
}