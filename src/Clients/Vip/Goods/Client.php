<?php

namespace Young\Union\Clients\Vip\Goods;

use Young\Union\Clients\Vip\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    const SERVICE = 'com.vip.adp.api.open.service.UnionGoodsService';

    /**
     * 精选商品
     * channelType  Integer 否   频道类型:0-超高佣，1-出单爆款; 当请求类型为频道时必传
     * page    Integer 是   页码
     * pageSize    Integer 否   分页大小:默认20，最大100
     * requestId   String  是   请求id：调用方自行定义，用于追踪请求，单次请求唯一，建议使用UUID
     * queryReputation Boolean 否   是否查询商品评价信息:默认不查询，该数据在详情页有返回，没有特殊需求，建议不查询  
     * queryStoreServiceCapability Boolean 否   是否查询店铺服务能力信息:默认不查询，该数据在详情页有返回，没有特殊需求，建议不查询
     * sourceType  Integer 否   请求源类型：0-频道，1-组货
     * jxCode  String  否   精选组货码：当请求源类型为组货时必传
     * fieldName   String  否   排序字段: COMMISSION-佣金，PRICE-价格,COMM_RATIO-佣金比例，DISCOUNT-折扣  
     * order   Integer 否   排序顺序：0-正序，1-逆序，默认正序
     * queryStock  Boolean 否   是否查询库存:默认不查询  
     * queryActivity   Boolean 否   是否查询商品活动信息:默认不查询  
     * queryPrepay Boolean 否   是否查询商品预付信息:默认不查询  
     * commonParams    CommonParams    否   通用参数  
     * commonParams.plat    Integer 否   用户平台：1-PC,2-APP,3-小程序,不传默认为APP
     * commonParams.deviceType  String  否   设备号类型：IMEI，IDFA，OAID，有则传入 
     * commonParams.deviceValue String  否   设备号MD5加密后的值，有则传入  
     * commonParams.ip  String  否   用户ip地址
     * commonParams.longitude   String  否   经度 如:29.590961456298828
     * commonParams.latitude    String  否   纬度 如:106.51573181152344
     * vendorCode  String  否   工具商code
     * chanTag String  否   pid
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        if (!isset($params['requestId']) ) {
            $params['requestId'] = \Young\Union\uuid('vip');
        }

        if ( !isset($params['page']) ) {
            $params['page'] = 1;
        }

        if (!isset($params['channelType']) ) {
            throw new ClientException('channelType required', SDK::INVALID_ARGUMENT);
        }

        return $this->send(self::SERVICE, 'goodsList', [
            'request' => $params
        ], $requestAsync);
    }

    /**
     * 搜索商品
     * keyword  String  是   关键词
     * fieldName   String  否   排序字段   
     * order   Integer 否   排序顺序：0-正序，1-逆序，默认正序
     * page    Integer 是   页码 
     * pageSize    Integer 否   页面大小：默认20,最大50 
     * requestId   String  是   请求id：调用方自行定义，用于追踪请求，单次请求唯一，建议使用UUID
     * priceStart  String  否   价格区间---start
     * priceEnd    String  否   价格区间---end 
     * queryReputation Boolean 否   是否查询商品评价信息:默认不查询，该数据在详情页有返回，没有特殊需求，建议不查询，影响接口性能
     * queryStoreServiceCapability Boolean 否   是否查询店铺服务能力信息：默认不查询，该数据在详情页有返回，没有特殊需求，建议不查询，影响接口性能  
     * queryStock  Boolean 否   是否查询库存:默认不查询   
     * queryActivity   Boolean 否   是否查询商品活动信息:默认不查询   
     * queryPrepay Boolean 否   是否查询商品预付信息:默认不查询   
     * commonParams    CommonParams    否   通用参数  
     * commonParams.plat    Integer 否   用户平台：1-PC,2-APP,3-小程序,不传默认为APP
     * commonParams.deviceType  String  否   设备号类型：IMEI，IDFA，OAID，有则传入 
     * commonParams.deviceValue String  否   设备号MD5加密后的值，有则传入  
     * commonParams.ip  String  否   用户ip地址
     * commonParams.longitude   String  否   经度 如:29.590961456298828
     * commonParams.latitude    String  否   纬度 如:106.51573181152344
     * vendorCode  String  否   工具商code
     * chanTag String  否   用户pid
     */
    public function search(array $params = [], $requestAsync = false)
    {
        if (!isset($params['requestId']) ) {
            $params['requestId'] = \Young\Union\uuid('vip');
        }

        if ( !isset($params['page']) ) {
            $params['page'] = 1;
        }

        if ( !isset($params['keyword']) ) {
            throw new ClientException('keyword required', SDK::INVALID_ARGUMENT);
        }

        return $this->send(self::SERVICE, 'query', [
            'request' => $params
        ], $requestAsync);
    }

    /**
     * 商品详情
     * goodsIdList  List<String>    是   商品id列表'
     * requestId   String  是   请求id：UUID
     * chanTag String  否   自定义渠道标识,同推广位
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        if (!isset($params['requestId']) ) {
            $params['requestId'] = \Young\Union\uuid('vip');
        }

        if ( !isset($params['goodsIdList']) || !is_array($params['goodsIdList']) ) {
            throw new ClientException('goodsIdList required and must be an array', SDK::INVALID_ARGUMENT);
        }

        return $this->send(self::SERVICE, 'getByGoodsIds', $params, $requestAsync);
    }

    /**
     * 猜你喜欢商品推荐
     * page Integer 是   分页页码：从1开始       
     * pageSize    Integer 否   分页大小：默认20       
     * requestId   String  否   请求id：调用方自行定义，用于追踪请求，单次请求唯一，建议使用UUID     
     * inStock Integer 否   是否有货 1:有货 0:无货 默认1      
     * goodsSaleStats  Integer 否   商品售卖状态 1（在售）， 2（预热）， 3（在售+预热） 默认1       
     * offlineStore    Integer 否   筛选线下店商品：1只返线下店，0或者不传只返回特卖会      
     * commonParams    CommonParams    否   通用参数  
     * commonParams.plat    Integer 否   用户平台：1-PC,2-APP,3-小程序,不传默认为APP
     * commonParams.deviceType  String  否   设备号类型：IMEI，IDFA，OAID，有则传入 
     * commonParams.deviceValue String  否   设备号MD5加密后的值，有则传入  
     * commonParams.ip  String  否   用户ip地址
     * commonParams.longitude   String  否   经度 如:29.590961456298828
     * commonParams.latitude    String  否   纬度 如:106.51573181152344
     * chanTag String  否   自定义渠道标识     

     */
    public function guess(array $params = [], $requestAsync = false)
    {
        if (!isset($params['requestId']) ) {
            $params['requestId'] = \Young\Union\uuid('vip');
        }

        if ( !isset($params['page']) ) {
            $params['page'] = 1;
        }

        return $this->send(self::SERVICE, 'userRecommend', [
            'request' => $params
        ], $requestAsync);
    }
}