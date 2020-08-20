<?php

namespace Young\Union\Clients\Vip\Order;

use Young\Union\Clients\Vip\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    const SERVICE = 'com.vip.adp.api.open.service.UnionOrderService';

    /**
     * 获取订单
     * 支持参数
     * status   Short   否           订单状态:0-不合格，1-待定，2-已完结，该参数不设置默认代表全部状态
     * orderTimeStart  Long    否           订单时间起始 时间戳 单位毫秒
     * orderTimeEnd    Long    否           订单时间结束 时间戳 单位毫秒
     * page    Integer 是           页码：从1开始
     * pageSize    Integer 否           页面大小：默认20
     * requestId   String  是           请求id：调用方自行定义，用于追踪请求，单次请求唯一，建议使用UUID
     * updateTimeStart Long    否           更新时间-起始 时间戳 单位毫秒
     * updateTimeEnd   Long    否           下单时间-结束 时间戳 单位毫秒
     * orderSnList List<String>    否           订单号列表：当传入订单号列表时，订单时间和更新时间区间可不传入
     * vendorCode  String  否           vendorCode,工具商方式下会传入
     * chanTag String  否           渠道标识，即推广位PID
     */
    public function list(array $params = [], $requestAsync = false)
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        if (!isset($params['requestId']) ) {
            $params['requestId'] = \Young\Union\uuid('vip');
        }

        $data = [
            'queryModel' => $params
        ];

        return $this->send(self::SERVICE, 'orderList', $data, $requestAsync);
    }
}