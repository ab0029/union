<?php

namespace Young\Union\Clients\Jd\Order;

use Young\Union\Clients\Jd\Gateway;
use Young\Union\Clients\Jd\Result;
use GuzzleHttp\Promise\PromiseInterface;

class Client extends Gateway
{
    public static $maxAutoPage = 5;

    /**
     * 获取订单
     * 支持参数
     * pageNo
     * 页码，返回第几页结果
     *
     * pageSize
     * 每页包含条数，上限为500
     *
     * type
     * 订单时间查询类型(1：下单时间，2：完成时间，3：更新时间)
     *
     * time
     * 查询时间，建议使用分钟级查询，格式：yyyyMMddHH、yyyyMMddHHmm或yyyyMMddHHmmss，如201811031212 的查询范围从12:12:00--12:12:59
     *
     * childUnionId
     * 子站长ID（需要联系运营开通PID账户权限才能拿到数据），childUnionId和key不能同时传入
     *
     * key
     * 其他推客的授权key，查询工具商订单需要填写此项，childUnionid和key不能同时传入
     */
    public function list(array $params, $requestAsync = false)
    {
        if (!isset($params['pageNo'])) {
            $params['pageNo'] = 1;
        }
        if (!isset($params['pageSize'])) {
            $params['pageSize'] = 100;
        }
        if (!isset($params['type'])) {
            $params['type'] = 1;
        }
        if (!isset($params['time'])) {
            $params['time'] = date('YmdH');
        }

        return $this->send('jd.union.open.order.query', [
            'orderReq' => $params,
        ], $requestAsync);
    }

    /**
     * 获取某天的订单（异步并发拉取订单）
     * @param  string $date 日期
     * @return array        24小时集合数，自动处理下一个分页
     */
    public function dayList($date, int $deepPage = 5)
    {
        $dates = is_array($date) ? $date : func_get_args();

        $dates = array_unique(array_map(function($date){
            return date('Ymd', strtotime($date));
        }, $dates));

        $promises = [];
        foreach ($dates as $time) {
            for( $i = 0; $i < 24; $i++) {
                $params = [
                    'pageSize' => 500,
                    'time' => $time . str_pad((string) $i, 2, "0", STR_PAD_LEFT)
                ];
                $promises[] = $this->list($params, true)->then(function($response) use ($deepPage) {
                    return $this->nextListAsync($response, $deepPage, true);
                });
            }
        }

        $responses = \GuzzleHttp\Promise\unwrap($promises);

        return $responses;
    }

    public function nextListAsync(Result $response, int $deepPage = 1, bool $mergePreReponse = false) : PromiseInterface
    {
        // 检查下页还有无数据
        if ( $response['result.hasMore'] ) {
            $psrRequest = $response->getPsrRequest();
            $param_json = json_decode($psrRequest['query.param_json'], true);
            $query = $param_json['orderReq'];
            if ( $deepPage <= 0 ) {
                // 自动拉取下页超出最大次数
                return \GuzzleHttp\Promise\promise_for($response);
            }
            $deepPage--;
            $query['pageNo']++;
            return $this->list($query, true)->then(function($nextResponse) use ($response, $deepPage, $mergePreReponse) {
                if ( $mergePreReponse ) {
                    // 合并上一次请求的数据
                    $data = array_merge($response['result.data'] ?? [], $nextResponse['result.data'] ?? []);
                    $nextResponse->set('result.data', $data);
                }
                return $this->nextListAsync($nextResponse, $deepPage, $mergePreReponse);
            });
        } else {
            return \GuzzleHttp\Promise\promise_for($response);
        }
    }
}