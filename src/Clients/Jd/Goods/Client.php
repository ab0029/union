<?php

namespace Young\Union\Clients\Jd\Goods;

use Young\Union\Clients\Jd\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 精选商品
     * eliteId  int 是   22  1-好券商品,2-精选卖场,10-9.9包邮,15-京东配送,22-实时热销榜,23-为你推荐,24-数码家电,25-超市,26-母婴玩具,27-家具日用,28-美妆穿搭,29-医药保健,30-图书文具,31-今日必推,32-京东好物,33-京东秒杀,34-拼购商品,40-高收益榜,41-自营热卖榜,109-新品首发,110-自营,112-京东爆品,125-首购商品,129-高佣榜单,130-视频商品,153-历史最低价商品榜
     * pageIndex   int 否   1   页码，默认1
     * pageSize    int 否   20  每页数量，默认20，上限50，建议20
     * sortName    String  否   price   排序字段(price：单价, commissionShare：佣金比例, commission：佣金， inOrderCount30DaysSku：sku维度30天引单量，comments：评论数，goodComments：好评数)
     * sort    String  否   desc    asc,desc升降序,默认降序
     * pid String  否   618_618_618 联盟id_应用id_推广位id，三段式
     * fields  String  否   videoInfo   支持出参数据筛选，逗号','分隔，目前可用：videoInfo,documentInfo
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        if (!isset($params['eliteId'])) {
            throw new ClientException('eliteId required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.goods.jingfen.query', [
            'goodsReq' => $params,
        ], $requestAsync);
    }

    /**
     * 搜索商品
     * 查询商品及优惠券信息，返回的结果可调用转链接口生成单品或二合一推广链接。支持按SKUID、关键词、优惠券基本属性、是否拼购、是否爆款等条件查询，建议不要同时传入SKUID和其他字段，以获得较多的结果。支持按价格、佣金比例、佣金、引单量等维度排序。用优惠券链接调用转链接口时，需传入搜索接口link字段返回的原始优惠券链接，切勿对链接进行任何encode、decode操作，否则将导致转链二合一推广链接时校验失败。
     * cid1 Long    否   737 一级类目id
     * cid2    Long    否   738 二级类目id
     * cid3    Long    否   739 三级类目id
     * pageIndex   Integer 否   1   页码
     * pageSize    Integer 否   20  每页数量，单页数最大30，默认20
     * skuIds  Long[]  否   52,253,467,275,691  skuid集合(一次最多支持查询100个sku)，数组类型开发时记得加[]
     * keyword String  否   手机  关键词，字数同京东商品名称一致，目前未限制
     * pricefrom   Double  否   16.88   商品价格下限
     * priceto Double  否   19.95   商品价格上限
     * commissionShareStart    Integer 否   10  佣金比例区间开始
     * commissionShareEnd  Integer 否   50  佣金比例区间结束
     * owner   String  否   g   商品类型：自营[g]，POP[p]
     * sortName    String  否   price   排序字段(price：单价, commissionShare：佣金比例, commission：佣金， inOrderCount30Days：30天引单量， inOrderComm30Days：30天支出佣金)
     * sort    String  否   desc    asc,desc升降序,默认降序
     * isCoupon    Integer 否   1   是否是优惠券商品，1：有优惠券，0：无优惠券
     * isPG    Integer 否   1   是否是拼购商品，1：拼购商品，0：非拼购商品
     * pingouPriceStart    Double  否   16.88   拼购价格区间开始
     * pingouPriceEnd  Double  否   19.95   拼购价格区间结束
     * isHot   Integer 否   1   是否是爆款，1：爆款商品，0：非爆款商品
     * brandCode   String  否   7998    品牌code
     * shopId  Integer 否   45619   店铺Id
     * hasContent  Integer 否   1   1：查询内容商品；其他值过滤掉此入参条件。
     * hasBestCoupon   Integer 否   1   1：查询有最优惠券商品；其他值过滤掉此入参条件。
     * pid String  否   618_618_618 联盟id_应用iD_推广位id
     * fields  String  否   videoInfo   支持出参数据筛选，逗号','分隔，目前可用：videoInfo(视频信息),commentInfo(评论信息),hotWords(热词),similar(相似推荐商品),documentInfo(段子信息)
     * forbidTypes String  否   2,3,5   过滤规则，入参表示不展示该规则数据，支持多个逗号','分隔，2:OTC商品;3:加油卡;4:游戏充值卡;5:合约机;6:京保养;7:虚拟组套;8:定制商品
     * jxFlags Integer[]   否   [1,2,3] 京喜商品类型，1京喜、2京喜工厂直供、3京喜优选（包含3时可在京东APP购买），入参多个值表示或条件查询
     * shopLevelFrom   Double  否   3.5 支持传入0.0、2.5、3.0、3.5、4.0、4.5、4.9，默认为空表示不筛选评分
     */
    public function search(array $params = [], $requestAsync = false)
    {
        if ( isset($params['skuIds']) && !is_array($params['skuIds']) ) {
            throw new ClientException('skuIds must be a array', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.goods.query', [
            'goodsReqDTO' => $params,
        ], $requestAsync);
    }

    /**
     * 商品详情
     * 通过SKUID查询推广商品的名称、主图、类目、价格、物流、是否自营、30天引单数量等详细信息，支持批量获取。通常用于在媒体侧展示商品详情。
     * skuIds  String  是  5225346,7275691  京东skuID串，逗号分割，最多100个，开发示例如param_json={'skuIds':'5225346,7275691'}（非常重要 请大家关注：如果输入的sk串中某个skuID的商品不在推广中[就是没有佣金]，返回结果中不会包含这个商品的信息）
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['skuIds']) ) {
            throw new ClientException('skuIds required', SDK::INVALID_ARGUMENT);
        }

        if ( is_array($params['skuIds']) ) {
            $params['skuIds'] = implode(',', $params['skuIds']);
        }

        return $this->send('jd.union.open.goods.promotiongoodsinfo.query', $params, $requestAsync);
    }

    /**
     * 商品详情查询接口,大字段信息
     * skuIds   long[]  是   29357345299 skuId集合 
     * fields  String[]    否   'categoryInfo','imageInfo','baseBigFieldInfo','bookBigFieldInfo','videoBigFieldInfo'    查询域集合，不填写则查询全部
     */
    public function bigDetail(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['skuIds']) ) {
            throw new ClientException('skuIds required', SDK::INVALID_ARGUMENT);
        }

        if ( !is_array($params['skuIds']) ) {
            throw new ClientException('skuIds must be a array', SDK::INVALID_ARGUMENT);
        }

        if ( !isset($params['skuIds']) && !is_array($params['fields']) ) {
            throw new ClientException('fields must be a array', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.goods.bigfield.query', [
            'goodsReq' => $params,
        ], $requestAsync);
    }

    /**
     * 秒杀商品
     * skuIds   long[]  否   26,227,522,112,918  sku id集合，长度最大30
     * pageIndex   int 否   1   页码，默认1
     * pageSize    int 否   30  每页数量最大30，默认30
     * isBeginSecKill  int 否   1   是否返回未开始秒杀商品。1=返回，0=不返回
     * secKillPriceFrom    double  否   100 秒杀价区间开始（单位：元）
     * secKillPriceTo  double  否   1000    秒杀价区间结束
     * cid1    long    否   9192    一级类目
     * cid2    long    否   9194    二级类目
     * cid3    long    否   9226    三级类目
     * owner   String  否   g   g=自营，p=pop
     * commissionShareFrom double  否   2.5 佣金比例区间开始
     * commissionShareTo   double  否   15  佣金比例区间结束
     * sortName    String  否   seckillPrice    排序字段，可为空。 （默认搜索综合排序。允许的排序字段：seckillPrice、commissionShare、inOrderCount30Days、inOrderComm30Days）
     * sort    String  否   desc    desc=降序，asc=升序，可为空（默认降序）

     */
    public function seckill(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['skuIds']) && !is_array($params['skuIds']) ) {
            throw new ClientException('skuIds must be a array', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.goods.seckill.query', [
            'goodsReq' => $params,
        ], $requestAsync);
    }

    /**
     * 猜你喜欢商品推荐
     * 输入频道id、userid即可获取个性化推荐的商品信息，目前联盟推荐的精选频道包含猜你喜欢、实时热销、大额券、9.9包邮等各种实时数据，适用于toc搭建频道页，千人千面商品推荐模块场景。千人千面推荐场景下，请勿传入排序参数，以免影响推荐效果。
     * eliteId  int 是   1   频道ID：1.猜你喜欢、2.实时热销、3.大额券、4.9.9包邮
     * pageIndex   int 否   1   页码
     * pageSize    int 否   10  每页数量，最大10
     * sortName    String  否   price   排序字段(lowestPrice：最低价, inOrderCount30DaysSku：sku维度30天引单量，comments：评论数，goodComments：好评数)，猜你喜欢推荐场景请勿传入
     * sort    String  否   asc asc,desc升降序,默认降序，猜你喜欢推荐场景请勿传入
     * pid String  否   无   联盟id_应用id_推广位id，三段式
     * subUnionId  String  否   618_18_c35***e6a    子联盟ID（需申请，申请方法请见https://union.jd.com/helpcenter/13246-13247-46301），该字段为自定义参数，建议传入字母数字和下划线的格式
     * siteId  String  否   435676  站点ID是指在联盟后台的推广管理中的网站Id、APPID（1、通用转链接口禁止使用社交媒体id入参；2、订单来源，即投放链接的网址或应用必须与传入的网站ID/AppID备案一致，否则订单会判“无效-来源与备案网址不符”）
     * positionId  String  否   1   推广位id
     * ext1    String  否   100_618_618 系统扩展参数，无需传入
     * skuId   long    否   1111    预留字段，请勿传入
     * hasCoupon   int 否   1   1：只查询有最优券商品，不传值不做限制
     * userIdType  int 否   32  用户ID类型，传入此参数可获得个性化推荐结果，32：苹果移动设备idfa，32768：android oaid
     * userId  String  否   6D18**-**-**-**-****    userIdType对应的用户设备ID，传入此参数可获得个性化推荐结果
     * fields  String  否   videoInfo   支持出参数据筛选，逗号','分隔，目前可用：videoInfo(视频信息)

     */
    public function guess(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['eliteId']) ) {
            throw new ClientException('eliteId required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.goods.material.query', [
            'goodsReq' => $params,
        ], $requestAsync);
    }
}