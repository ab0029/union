<?php

namespace Young\Union\Clients\Kaola\Goods;

use Young\Union\Clients\Kaola\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 一级类目ID   一级类目名称
     * 110022  宠物
     * 9691    服装鞋靴
     * 381 个人洗护
     * 836 环球美食
     * 372 家居生活
     * 437 美容彩妆
     * 438 母婴
     * 8096    汽车用品
     * 8115    生鲜
     * 1092    手表配饰
     * 440 数码家电
     * 1025    箱包
     * 837 医药健康
     * 7578    运动户外
     */

    /**
     * 精选商品，这个接口只返回商品ID
     * poolName String  Y   精选商品池名称
     *                      1-每日平价商品
     *                      2-高佣必推商品
     *                      3-新人专享商品
     *                      4-会员专属商品
     *                      5-低价包邮商品
     *                      6-考拉自营爆款
     *                      7-考拉商家爆款
     *                      8-黑卡用户最爱买商品
     *                      9-美妆个护热销品
     *                      10-食品保健热销品
     *                      11-母婴热销品
     *                      12-时尚热销品
     *                      13-家居宠物热销品
     *                      14-每日秒杀商品
     *                      15-黑卡好价商品
     *                      16-高转化好物商品
     *                      17-开卡一单省回商品
     *                      18-会员专属促销选品池
     * pageNo  Integer N   页码
     * pageSize    Integer N   每页数量  默认200（最大值）最小20
     *
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['poolName']) ) {
            throw new ClientException('poolName required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('kaola.zhuanke.api.querySelectedGoods', $params, $requestAsync);
    }

    /**
     * 搜索商品
     * trackingCode1    String  N   渠道参数1
     * trackingCode2   String  N   渠道参数2
     * keyWord String  Y   搜索关键字
     * type    Integer N   排序方式 0 综合排序 1 价格排序 2 销量排序 10 佣金比例排序 默认0排序方式
     * desc    BOOLEAN N   是否降序，只对价格排序方式有效，默认降序 true 降序 flase 升序
     * pageNo  Integer N   页码 默认第一页
     * pageSize    Integer N   每次查询数量 默认20
     * skipNoCommission    BOOLEAN N   是否跳过没有佣金的商品 默认跳过 true 跳过 false 不跳过
     */
    public function search(array $params = [], $requestAsync = false)
    {
        $params['keyWord'] = $params['keyWord'] ?? $params['keyword'] ?? null;
        unset($params['keyword']);
        if ( !isset($params['keyWord']) ) {
            throw new ClientException('keyWord required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('kaola.zhuanke.api.searchGoods', $params, $requestAsync);
    }

    /**
     * 商品详情
     * goodsIds String  Y   商品ID列表，多个ID用英文逗号分隔，每次限制20
     * trackingCode1   String  N   渠道参数1
     * trackingCode2   String  N   渠道参数2
     * type    int N   0 按照goodsIds维度（默认）1 按照goodsUrl
     * goodsUrl    String  N   解析出url中商品ID，每次只能传一个
     * needShortLink   String  N   是否需要短链接 N:不需要（响应速度快）其他：需要
     * needGroupBuyInfo    String  N   是否需要拼团信息 N:不需要 其他：需要
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['goodsIds']) ) {
            throw new ClientException('goodsIds required', SDK::INVALID_ARGUMENT);
        }

        if ( is_array($params['goodsIds']) ) {
            $params['goodsIds'] = implode(',', $params['goodsIds']);
        }

        return $this->send('kaola.zhuanke.api.queryGoodsInfo', $params, $requestAsync);
    }

    /**
     * 猜你喜欢商品推荐，目前没有官方接口，用推荐代替
     */
    public function guess(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['poolName']) ) {
            $params['poolName'] = mt_rand(1,18);
        }

        $num = $params['num'] ?? 20;

        $promise = $this->recommend($params, true)->then(function($result) use ($num) {
            $data = $result['data.goodsIdList'] ?? [];
            $ids = $data ? array_rand($data, min(count($data), $num)) : [];
            if ( $ids ) {
                $goodsIds = [];
                foreach ($ids as $key) {
                    $goodsIds[] = $data[$key];
                }
                return $this->detail(['goodsIds' => $goodsIds], true);
            }
            return \GuzzleHttp\Promise\promise_for($result);
        }, function(\Exception $exception) {
            return new ClientException(
                $exception->getMessage(),
                SDK::SERVER_UNREACHABLE,
                $exception
            );
        });

        if ( !$requestAsync ) {
            $result = $promise->wait();
            if ( $result instanceof ClientException ) {
                throw $result;
            }
            return $result;
        }

        return $promise;
    }
}