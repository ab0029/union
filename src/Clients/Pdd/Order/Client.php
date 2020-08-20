<?php

namespace Young\Union\Clients\Pdd\Order;

use Young\Union\Clients\Pdd\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 获取订单
     * 支持参数
     * end_update_time  LONG    必填  查询结束时间，和开始时间相差不能超过24小时。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
     * page    INTEGER 非必填 第几页，从1到10000，默认1，注：使用最后更新时间范围增量同步时，必须采用倒序的分页方式（从最后一页往回取）才能避免漏单问题。
     * page_size   INTEGER 非必填 返回的每页结果订单数，默认为100，范围为10到100，建议使用40~50，可以提高成功率，减少超时数量。
     * return_count    BOOLEAN 非必填 是否返回总数，默认为true，如果指定false, 则返回的结果中不包含总记录数，通过此种方式获取增量数据，效率在原有的基础上有80%的提升。
     * start_update_time   LONG    必填  最近90天内多多进宝商品订单更新时间--查询时间开始。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
     * query_order_type    INTEGER 非必填 订单类型：1-推广订单；2-直播间订单
     */
    public function list(array $params = [], $requestAsync = false)
    {
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }

        if (!isset($params['start_update_time'])) {
            throw new ClientException('start_update_time required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['end_update_time'])) {
            throw new ClientException('end_update_time required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('pdd.ddk.order.list.increment.get', $params, $requestAsync);
    }
}