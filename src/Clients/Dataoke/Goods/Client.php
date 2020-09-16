<?php

namespace Young\Union\Clients\Dataoke\Goods;

use Young\Union\Clients\Dataoke\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 精选商品
     * pageId String 是 分页id：常规分页方式，请直接传入对应页码（比如：1,2,3……）
     * pageSize Number 是 每页返回条数，每页条数支持输入10,20，50,100。默认50条
     * PriceCid String 否 价格区间，1表示10~20元区；2表示20~40元区；3表示40元以上区；默认为1
     * cids String 否 大淘客的一级分类id，如果需要传多个，以英文逗号相隔，如：”1,2,3”。1 -女装，2 -母婴，3 -美妆，4 -居家日用，5 -鞋品，6 -美食，7 -文娱车品，8 -数码家电，9 -男装，10 -内衣，11 -箱包，12 -配饰，13 -户外运动，14 -家装家纺。不填默认全部
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            $params['version'] = 'v1.0.0';
        }

        if (!isset($params['pageId'])) {
            $params['pageId'] = 1;
        }

        if (!isset($params['pageSize'])) {
            $params['pageSize'] = 20;
        }

        if ( isset($params['cids']) && is_array($params['cids']) ) {
            $params['cids'] = implode(',', $params['cids']);
        }

        return $this->send('GET', 'api/goods/explosive-goods-list', $params, $requestAsync);
    }

    /**
     * 大淘客搜索商品，如果需要走淘宝官方搜索的，用unionSearch
     * pageSize   Number  是   每页条数，默认为100，最大值200，若小于10，则按10条处理，每页条数仅支持输入10,50,100,200
     * pageId    String  是   分页id，默认为1，支持传统的页码分页方式和scroll_id分页方式，根据用户自身需求传入值。示例1：商品入库，则首次传入1，后续传入接口返回的pageid，接口将持续返回符合条件的完整商品列表，该方式可以避免入口商品重复；示例2：根据pagesize和totalNum计算出总页数，按照需求返回指定页的商品（该方式可能在临近页取到重复商品）
     * keyWords  String  是   关键词搜索
     * cids  String  否   大淘客的一级分类id，如果需要传多个，以英文逗号相隔，如：”1,2,3”。当一级类目id和二级类目id同时传入时，会自动忽略二级类目id，一级分类id请求详情：1 -女装，2 -母婴，3 -美妆，4 -居家日用，5 -鞋品，6 -美食，7 -文娱车品，8 -数码家电，9 -男装，10 -内衣，11 -箱包，12 -配饰，13 -户外运动，14 -家装家纺
     * subcid    Number  否   大淘客的二级类目id，通过超级分类API获取。仅允许传一个二级id，当一级类目id和二级类目id同时传入时，会自动忽略二级类目id
     * juHuaSuan Number  否   是否聚划算，1-聚划算商品，0-所有商品，不填默认为0
     * taoQiangGou   Number  否   是否淘抢购，1-淘抢购商品，0-所有商品，不填默认为0
     * tmall Number  否   是否天猫商品，1-天猫商品，0-非天猫商品，不填默认为所有商品
     * tchaoshi  Number  否   是否天猫超市商品，1-天猫超市商品，0-所有商品，不填默认为0
     * goldSeller    Number  否   是否金牌卖家，1-金牌卖家，0-所有商品，不填默认为0
     * haitao    Number  否   是否海淘商品，1-海淘商品，0-所有商品，不填默认为0
     * brand Number  否   是否品牌商品，1-品牌商品，0-所有商品，不填默认为0
     * brandIds  String  否   品牌id，当brand传入0时，再传入brandIds将获取不到结果。品牌id可以传多个，以英文逗号隔开，如：”345,321,323”
     * priceLowerLimit   Number  否   价格（券后价）下限
     * priceUpperLimit   Number  否   价格（券后价）上限
     * couponPriceLowerLimit Number  否   最低优惠券面额
     * commissionRateLowerLimit  Number  否   最低佣金比率
     * monthSalesLowerLimit  Number  否   最低月销量
     * sort  String  否   排序字段，默认为0，0-综合排序，1-商品上架时间从新到旧，2-销量从高到低，3-领券量从高到低，4-佣金比例从高到低，5-价格（券后价）从高到低，6-价格（券后价）从低到高
     * freeshipRemoteDistrict    Number  否   偏远地区包邮，1-是，0-非偏远地区，不填默认所有商品
     */
    public function search(array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            $params['version'] = 'v2.1.2';
        }

        if (!isset($params['pageId'])) {
            $params['pageId'] = 1;
        }

        if (!isset($params['pageSize'])) {
            $params['pageSize'] = 20;
        }

        $params['keyWords'] = $params['keyWords'] ?? $params['keyword'] ?? $params['keywords'] ?? null;
        unset($params['keyword'], $params['keywords']);
        if ( !isset($params['keyWords']) ) {
            throw new ClientException('keyWords required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('GET', 'api/goods/get-dtk-search-goods', $params, $requestAsync);
    }

    /**
     * 淘宝联盟搜索商品
     * pageNo Number 是 第几页，默认1
     * pageSize Number 是 每页条数， 默认20，范围值1~100
     * keyWords String 是 查询词
     * sort String 否 排序指标：销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi），价格（price）,排序方式：排序_des（降序），排序_asc（升序）,示例：升序查询销量：total_sales_asc
     * source Number 否 是否商城商品，设置为1表示该商品是属于淘宝商城商品，设置为非1或不设置表示不判断这个属性（和overseas字段冲突，若已请求source，请勿再请求overseas）
     * overseas Number 否 是否海外商品，设置为1表示该商品是属于海外商品，设置为非1或不设置表示不判断这个属性（和source字段冲突，若已请求overseas，请勿再请求source）
     * endPrice Number 否 折扣价范围上限，单位：元
     * startPrice Number 否 折扣价范围下限，单位：元
     * startTkRate Number 否 媒体淘客佣金比率下限，如：1234表示12.34%
     * endTkRate Number 否 商品筛选-淘客佣金比率上限，如：1234表示12.34%
     * hasCoupon Boolean 否 是否有优惠券，设置为true表示该商品有优惠券，设置为false或不设置表示不判断这个属性
     * specialId string 否 会员运营id
     * channelId string 否 渠道id将会和传入的pid进行验证，验证通过将正常转链，请确认填入的渠道id是正确的channelId对应联盟的relationId
     */
    public function unionSearch(array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            $params['version'] = 'v2.1.0';
        }

        if (!isset($params['pageNo'])) {
            $params['pageNo'] = 1;
        }

        if (!isset($params['pageSize'])) {
            $params['pageSize'] = 20;
        }

        $params['keyWords'] = $params['keyWords'] ?? $params['keyword'] ?? $params['keywords'] ?? null;
        unset($params['keyword'], $params['keywords']);
        if ( !isset($params['keyWords']) ) {
            throw new ClientException('keyWords required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('GET', 'api/tb-service/get-tb-service', $params, $requestAsync);
    }

    /**
     * 商品详情
     * id Number 是 大淘客商品id，请求时id或goodsId必填其中一个，若均填写，将优先查找当前单品id
     * goodsId String 否 淘宝商品id，id或goodsId必填其中一个，若均填写，将优先查找当前单品id
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            $params['version'] = 'v1.2.3';
        }

        if ( !isset($params['id']) && !isset($params['goodsId']) ) {
            throw new ClientException('id or goodsId required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('GET', 'api/goods/get-goods-details', $params, $requestAsync);
    }

    /**
     * 猜你喜欢商品推荐
     * id Number  是   大淘客的商品id
     * size  Number  否   每页条数，默认10 ， 最大值100
     */
    public function guess(array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            $params['version'] = 'v1.2.2';
        }

        if ( !isset($params['id']) ) {
            throw new ClientException('id required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('GET', 'api/goods/list-similer-goods-by-open', $params, $requestAsync);
    }
}