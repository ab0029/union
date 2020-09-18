<?php

namespace Young\Union\Clients\Kaola\Order;

use Young\Union\Clients\Kaola\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 获取订单
     * 支持参数
     * type Integer Y   1:根据下单时间段查询 2:根据订单号查询 3:根据更新时间查询
     * startDate   Long    N   毫秒数，查询开始时间 Type为1，3必填
     * endDate Long    N   毫秒数，查询结束时间 Type为1，3必填
     * orderId String  N   订单号 Type为2必填
     */
    public function list(array $params = [], $requestAsync = false)
    {
        if (!isset($params['type'])) {
            throw new ClientException('type required', SDK::INVALID_ARGUMENT);
        }

        if ( in_array($params['type'], [1,3]) ) {
            if (!isset($params['startDate'])) {
                throw new ClientException('startDate required', SDK::INVALID_ARGUMENT);
            }

            if (!isset($params['endDate'])) {
                throw new ClientException('endDate required', SDK::INVALID_ARGUMENT);
            }
        }

        if ( $params['type'] == 2 && isset($params['orderId'])) {
            if (!isset($params['orderId'])) {
                throw new ClientException('orderId required', SDK::INVALID_ARGUMENT);
            }
        }

        return $this->send('kaola.zhuanke.api.queryOrderInfo', $params, $requestAsync);
    }

    /**
     * 获取某天的订单（异步并发拉取订单）
     * @param  string $date 日期
     * @return array        24小时集合数，自动处理下一个分页
     */
    public function dayList($date, $type = 1, array $extendsPrarms = [])
    {
        $dates = is_array($date) ? $date : [$date];

        $dates = array_unique(array_map(function($date){
            return date('Y-m-d', strtotime($date));
        }, $dates));

        $promises = [];
        foreach ($dates as $time) {
            for( $i = 0; $i < 24; $i++) {
                $params = array_merge($extendsPrarms, [
                    'startDate' => strtotime(sprintf('%s %s:00:00', $time, str_pad((string) $i, 2, "0", STR_PAD_LEFT))) . '000',
                    'endDate' => strtotime(sprintf('%s %s:59:59', $time, str_pad((string) $i, 2, "0", STR_PAD_LEFT))) . '000',
                    'type' => in_array((string) $type, [1, 2, 3]) ? $type : 1,
                ]);
                $promises[] = $this->list($params, true)->then(null, function(GuzzleException $exception) {
                    return new ClientException(
                        $exception->getMessage(),
                        SDK::SERVER_UNREACHABLE,
                        $exception
                    );
                });
            }
        }

        $responses = \GuzzleHttp\Promise\unwrap($promises);

        return $responses;
    }
}