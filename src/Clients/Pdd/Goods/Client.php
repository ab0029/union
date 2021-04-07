<?php

namespace Young\Union\Clients\Pdd\Goods;

use Young\Union\Clients\Pdd\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 精选商品 多多进宝商品推荐API
     * activity_tags    INTEGER[]   非必填 活动商品标记数组，例：[4,7]，4-秒杀，7-百亿补贴，31-品牌黑标，10564-精选爆品-官方直推爆款，10584-精选爆品-团长推荐，24-品牌高佣，其他的值请忽略
     * cat_id  LONG    非必填 猜你喜欢场景的商品类目，20100-百货，20200-母婴，20300-食品，20400-女装，20500-电器，20600-鞋包，20700-内衣，20800-美妆，20900-男装，21000-水果，21100-家纺，21200-文具,21300-运动,21400-虚拟,21500-汽车,21600-家装,21700-家具,21800-医药;
     * channel_type    INTEGER 非必填 进宝频道推广商品: 1-今日销量榜,3-相似商品推荐,4-猜你喜欢(和进宝网站精选一致),5-实时热销榜,6-实时收益榜。默认值5
     * custom_parameters   STRING  非必填 自定义参数，为链接打上自定义标签；自定义参数最长限制64个字节；格式为： {"uid":"11111","sid":"22222"} ，其中 uid 为用户唯一标识，可自行加密后传入，每个用户仅且对应一个标识，必填； sid 为上下文信息标识，例如sessionId等，非必填。该json字符串中也可以加入其他自定义的key。
     * goods_sign_list STRING[]    非必填 商品goodsSign列表，相似商品推荐场景时必传，仅取数组的第一位，例如：["c9r2omogKFFAc7WBwvbZU1ikIb16_J3CTa8HNN"]。goodsSign是加密后的goodsId, goodsId已下线，请使用goodsSign来替代。使用说明：https://jinbao.pinduoduo.com/qa-system?questionId=252
     * limit   INTEGER 非必填 一页请求数量；默认值 ： 20
     * list_id STRING  非必填 翻页时建议填写前页返回的list_id值
     * offset  INTEGER 非必填 从多少位置开始请求；默认值 ： 0，offset需是limit的整数倍，仅支持整页翻页
     * pid STRING  非必填 推广位id
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        if ( isset($params['activity_tags']) && !is_array($params['activity_tags']) ) {
            throw new ClientException('activity_tags must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( isset($params['goods_sign_list']) && !is_array($params['goods_sign_list']) ) {
            throw new ClientException('goods_sign_list must be a array', SDK::INVALID_ARGUMENT);
        }

        return $this->send('pdd.ddk.goods.recommend.get', $params, $requestAsync);
    }

    /**
     * 搜索商品
     * activity_tags    INTEGER[]   非必填 活动商品标记数组，例：[4,7]，4-秒杀，7-百亿补贴，31-品牌黑标，10564-精选爆品-官方直推爆款，10584-精选爆品-团长推荐，24-品牌高佣，其他的值请忽略
     * block_cats  INTEGER[]   非必填 自定义屏蔽一级/二级/三级类目ID，自定义数量不超过20个;使用pdd.goods.cats.get接口获取cat_id
     * block_cat_packages  INTEGER[]   非必填 屏蔽商品类目包：1-拼多多小程序屏蔽的类目&关键词;2-虚拟类目;3-医疗器械;4-处方药;5-非处方药
     * cat_id  LONG    非必填 商品类目ID，使用pdd.goods.cats.get接口获取
     * custom_parameters   STRING  非必填 自定义参数，为链接打上自定义标签；自定义参数最长限制64个字节；格式为： {"uid":"11111","sid":"22222"} ，其中 uid 为用户唯一标识，可自行加密后传入，每个用户仅且对应一个标识，必填； sid 为上下文信息标识，例如sessionId等，非必填。该json字符串中也可以加入其他自定义的key。
     * goods_sign_list STRING[]    非必填 商品goodsSign列表，例如：["c9r2omogKFFAc7WBwvbZU1ikIb16_J3CTa8HNN"]，支持通过goodsSign查询商品。goodsSign是加密后的goodsId, goodsId已下线，请使用goodsSign来替代。使用说明：https://jinbao.pinduoduo.com/qa-system?questionId=252
     * is_brand_goods  BOOLEAN 非必填 是否为品牌商品
     * keyword STRING  非必填 商品关键词，与opt_id字段选填一个或全部填写。可支持goods_id、拼多多链接（即拼多多app商详的链接）、进宝长链/短链（即为pdd.ddk.goods.promotion.url.generate接口生成的长短链）
     * list_id STRING  非必填 翻页时建议填写前页返回的list_id值
     * merchant_type   INTEGER 非必填 店铺类型，1-个人，2-企业，3-旗舰店，4-专卖店，5-专营店，6-普通店（未传为全部）
     * merchant_type_list  INTEGER[]   非必填 店铺类型数组，例如：[1,2]
     * opt_id  LONG    非必填 商品标签类目ID，使用pdd.goods.opt.get获取
     * page    INTEGER 非必填 默认值1，商品分页数
     * page_size   INTEGER 非必填 默认100，每页商品数量
     * pid STRING  非必填 推广位id
     * range_list  OBJECT[]    非必填 筛选范围列表 样例：[{"range_id":0,"range_from":1,"range_to":1500},{"range_id":1,"range_from":1,"range_to":1500}]
     * range_from  LONG    非必填 区间的开始值
     * range_id    INTEGER 非必填 0，最小成团价 1，券后价 2，佣金比例 3，优惠券价格 4，广告创建时间 5，销量 6，佣金金额 7，店铺描述分 8，店铺物流分 9，店铺服务分 10， 店铺描述分击败同行业百分比 11， 店铺物流分击败同行业百分比 12，店铺服务分击败同行业百分比 13，商品分 17 ，优惠券/最小团购价 18，过去两小时pv 19，过去两小时销量
     * range_to    LONG    非必填 区间的结束值
     * sort_type   INTEGER 非必填 排序方式:0-综合排序;1-按佣金比率升序;2-按佣金比例降序;3-按价格升序;4-按价格降序;5-按销量升序;6-按销量降序;7-优惠券金额排序升序;8-优惠券金额排序降序;9-券后价升序排序;10-券后价降序排序;11-按照加入多多进宝时间升序;12-按照加入多多进宝时间降序;13-按佣金金额升序排序;14-按佣金金额降序排序;15-店铺描述评分升序;16-店铺描述评分降序;17-店铺物流评分升序;18-店铺物流评分降序;19-店铺服务评分升序;20-店铺服务评分降序;27-描述评分击败同类店铺百分比升序，28-描述评分击败同类店铺百分比降序，29-物流评分击败同类店铺百分比升序，30-物流评分击败同类店铺百分比降序，31-服务评分击败同类店铺百分比升序，32-服务评分击败同类店铺百分比降序
     * with_coupon BOOLEAN 非必填 是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品
     */
    public function search(array $params = [], $requestAsync = false)
    {
        if ( isset($params['activity_tags']) && !is_array($params['activity_tags']) ) {
            throw new ClientException('activity_tags must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( isset($params['merchant_type_list']) && !is_array($params['merchant_type_list']) ) {
            throw new ClientException('merchant_type_list must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( isset($params['range_list']) && !is_array($params['range_list']) ) {
            throw new ClientException('range_list must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( isset($params['goods_sign_list']) && !is_array($params['goods_sign_list']) ) {
            throw new ClientException('goods_sign_list must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( isset($params['block_cats']) && !is_array($params['block_cats']) ) {
            throw new ClientException('block_cats must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( isset($params['block_cat_packages']) && !is_array($params['block_cat_packages']) ) {
            throw new ClientException('block_cat_packages must be a array', SDK::INVALID_ARGUMENT);
        }

        // if ( !isset($params['pid']) ) {
        //     throw new ClientException('pid required', SDK::INVALID_ARGUMENT);
        // }

        // if ( !isset($params['custom_parameters']) ) {
        //     throw new ClientException('custom_parameters required', SDK::INVALID_ARGUMENT);
        // }

        return $this->send('pdd.ddk.goods.search', $params, $requestAsync);
    }

    /**
     * 商品详情
     * custom_parameters    STRING  非必填 自定义参数
     * goods_sign  STRING  非必填  商品goodsSign，支持通过goodsSign查询商品。goodsSign是加密后的goodsId, goodsId已下线，请使用goodsSign来替代。使用说明：https://jinbao.pinduoduo.com/qa-system?questionId=252
     * pid STRING  非必填 推广位id
     * plan_type   INTEGER 非必填 佣金优惠券对应推广类型，3：专属 4：招商
     * search_id   STRING  非必填 搜索id，建议填写，提高收益。来自pdd.ddk.goods.recommend.get、pdd.ddk.goods.search、pdd.ddk.top.goods.list.query等接口
     * zs_duo_id   LONG    非必填 招商多多客ID
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        return $this->send('pdd.ddk.goods.detail', $params, $requestAsync);
    }

    /**
     * 获取商品基本信息
     * goods_id_list    LONG[]  必填  商品id
     */
    public function baseDetail(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['goods_id_list']) ) {
            throw new ClientException('goods_id_list required', SDK::INVALID_ARGUMENT);
        }

        if ( !is_array($params['goods_id_list']) ) {
            throw new ClientException('goods_id_list must be a array', SDK::INVALID_ARGUMENT);
        }

        return $this->send('pdd.ddk.goods.basic.info.get', $params, $requestAsync);
    }

    /**
     * 多多客获取爆款排行商品接口
     * limit    INTEGER 非必填 请求数量；默认值 ： 400
     * list_id STRING  非必填 翻页时建议填写前页返回的list_id值
     * offset  INTEGER 非必填 从多少位置开始请求；默认值 ： 0
     * p_id    STRING  非必填 推广位id
     * sort_type   INTEGER 非必填 1-实时热销榜；2-实时收益榜
     * custom_parameters   STRING  非必填 自定义参数，为链接打上自定义标签；自定义参数最长限制64个字节；格式为： {"uid":"11111","sid":"22222"} ，其中 uid 用户唯一标识，可自行加密后传入，每个用户仅且对应一个标识，必填； sid 上下文信息标识，例如sessionId等，非必填。该json字符串中也可以加入其他自定义的key
     */
    public function top(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['pid']) ) {
            throw new ClientException('pid required', SDK::INVALID_ARGUMENT);
        }

        if ( !isset($params['custom_parameters']) ) {
            throw new ClientException('custom_parameters required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('pdd.ddk.top.goods.list.query', $params, $requestAsync);
    }

    /**
     * 猜你喜欢商品推荐
     * activity_tags    INTEGER[]   非必填 活动商品标记数组，例：[4,7]，4-秒杀，7-百亿补贴，31-品牌黑标，10564-精选爆品-官方直推爆款，10584-精选爆品-团长推荐，24-品牌高佣，其他的值请忽略
     * cat_id  LONG    非必填 猜你喜欢场景的商品类目，20100-百货，20200-母婴，20300-食品，20400-女装，20500-电器，20600-鞋包，20700-内衣，20800-美妆，20900-男装，21000-水果，21100-家纺，21200-文具,21300-运动,21400-虚拟,21500-汽车,21600-家装,21700-家具,21800-医药;
     * channel_type    INTEGER 非必填 进宝频道推广商品: 1-今日销量榜,3-相似商品推荐,4-猜你喜欢(和进宝网站精选一致),5-实时热销榜,6-实时收益榜。默认值5
     * custom_parameters   STRING  非必填 自定义参数，为链接打上自定义标签；自定义参数最长限制64个字节；格式为： {"uid":"11111","sid":"22222"} ，其中 uid 为用户唯一标识，可自行加密后传入，每个用户仅且对应一个标识，必填； sid 为上下文信息标识，例如sessionId等，非必填。该json字符串中也可以加入其他自定义的key。
     * goods_sign_list STRING[]    非必填 商品goodsSign列表，相似商品推荐场景时必传，仅取数组的第一位，例如：["c9r2omogKFFAc7WBwvbZU1ikIb16_J3CTa8HNN"]。goodsSign是加密后的goodsId, goodsId已下线，请使用goodsSign来替代。使用说明：https://jinbao.pinduoduo.com/qa-system?questionId=252
     * limit   INTEGER 非必填 一页请求数量；默认值 ： 20
     * list_id STRING  非必填 翻页时建议填写前页返回的list_id值
     * offset  INTEGER 非必填 从多少位置开始请求；默认值 ： 0，offset需是limit的整数倍，仅支持整页翻页
     * pid STRING  非必填 推广位id
     */
    public function guess(array $params = [], $requestAsync = false)
    {
        $params = array_merge($params, ['channel_type' => 4]);

        if ( !isset($params['pid']) ) {
            throw new ClientException('pid required', SDK::INVALID_ARGUMENT);
        }

        if ( !isset($params['custom_parameters']) ) {
            throw new ClientException('custom_parameters required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('pdd.ddk.goods.recommend.get', $params, $requestAsync);
    }
}