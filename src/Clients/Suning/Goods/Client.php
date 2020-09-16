<?php

namespace Young\Union\Clients\Suning\Goods;

use Young\Union\Clients\Suning\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 精选商品
     * eliteId  String  N   1   默认为1,,营销id。1-小编推荐；2-实时热销榜；3-当日热推榜；4-高佣爆款榜；5-团长招商榜；6-9块9专区
     * pageIndex   String  N   1   页码 默认0
     * cityCode    String  N   025 城市编码 默认025
     * picWidth    String  N   200 图片宽度
     * picHeight   String  N   200 图片高度
     * selfSupport String  N   0   是否苏宁自营。0：全部；1：是
     * size    String  N   10  每页数量
     * couponMark  String  N   1   1表示拿到券后价，不传按照以前逻辑取不到券后价
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['eliteId']) ) {
            $params['eliteId'] = 1;
        }

        return $this->send('suning.netalliance.selectrecommendcommodity.query', [
            'querySelectrecommendcommodity' => $params
        ], $requestAsync);
    }

    /**
     * 大淘客搜索商品，如果需要走淘宝官方搜索的，用unionSearch
     * pageIndex   String  N   1   页码 默认为1
     * keyword String  N   手机  关键字
     * saleCategoryCode    String  N   50000   销售目录ID
     * cityCode    String  N   025 城市编码 默认025
     * suningService   String  N   1   是否苏宁自营 默认为空，1：是
     * pgSearch    String  N   1   是否拼购 默认为空 1：是
     * startPrice  String  N   10.00   开始价格
     * endPrice    String  N   20.00   结束价格
     * sortType    String  N   1   排序规则 1：综合（默认） 2：销量由高到低 3：价格由高到低 4：价格由低到高 5：佣金比例由高到低 6：佣金金额由高到低 7：两个维度，佣金金额由高到低，销量由高到低8：近30天推广量由高到低9：近30天支出佣金金额由高到低。
     * picWidth    String  N   200 图片宽度 默认200
     * picHeight   String  N   200 图片高度 默认200
     * size    String  N   10  每页条数 默认10
     * branch  String  N   1   1：减枝 2：不减枝 sortType=1（综合） 默认不剪枝 其他排序默认剪枝
     * snfwservice String  N   1   是否苏宁服务 1:是
     * snhwg   String  N   1   是否苏宁国际 1:是
     * coupon  String  N   1   1:有券；其他:全部
     * couponMark  String  N   1   1表示拿到券后价，不传按照以前逻辑取不到券后价
     */
    public function search(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['keyword']) ) {
            throw new ClientException('keyword required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('suning.netalliance.searchcommodity.query', [
            'querySearchcommodity' => $params
        ], $requestAsync);
    }

    /**
     * 商品详情
     * goodsCode    String  Y   70055337    商品ID
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['goodsCode']) ) {
            throw new ClientException('goodsCode required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('suning.netalliance.unioninfomation.get', [
            'getUnionInfomation' => $params
        ], $requestAsync);
    }

    /**
     * 猜你喜欢商品推荐
     * cityCode String  N   025 所在地区的行政区号，若是四位区号，则去掉开头的“0”
     * commodityCode   String  Y   121307256   商品编码
     * supplierCode    String  Y   0000000000  商家编码
     * picType String  N   0   图片类型 0方图，1长图，2竖图,默认为0
     * picLocation String  N   2   图片位置
     * picWidth    String  N   640 图片宽度
     * picHeight   String  N   640 图片高度
     */
    public function guess(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['commodityCode']) ) {
            throw new ClientException('commodityCode required', SDK::INVALID_ARGUMENT);
        }

        if ( !isset($params['supplierCode']) ) {
            throw new ClientException('supplierCode required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('suning.netalliance.morerecommend.get', [
            'getMorerecommend' => $params
        ], $requestAsync);
    }
}