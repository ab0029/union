<?php

namespace Young\Union\Clients\Jd\Tool;

use Young\Union\Clients\Jd\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 网站/APP获取推广链接接口
     * materialId   String  是   https://item.jd.com/23484023378.html    推广物料    
     * siteId  String  是   435676  站点ID是指在联盟后台的推广管理中的网站Id、APPID（1、通用转链接口禁止使用社交媒体id入参；2、订单来源，即投放链接的网址或应用必须与传入的网站ID/AppID备案一致，否则订单会判“无效-来源与备案网址不符”）
     * positionId  long    否   6   推广位id
     * subUnionId  String  否   618_18_c35***e6a    子联盟ID（需申请，申请方法请见https://union.jd.com/helpcenter/13246-13247-46301），该字段为自定义参数，建议传入字母数字和下划线的格式
     * ext1    String  否   100_618_618 系统扩展参数，无需传入
     * protocol    int 否   已废弃 请勿再使用，后续会移除此参数，请求成功一律返回https协议链接
     * pid String  否   618_618_6018    联盟子站长身份标识，格式：子站长ID_子站长网站ID_子站长推广位ID
     * couponUrl   String  否   http://coupon.jd.com/ilink/get/get_coupon.action?XXXXXXX    优惠券领取链接，在使用优惠券、商品二合一功能时入参，且materialId须为商品详情页链接
     * giftCouponKey   String  否   xxx_coupon_key  礼金批次号
     */
    public function promotionCommonGet(array $params = [], $requestAsync = false)
    {
        if (!isset($params['materialId'])) {
            throw new ClientException('materialId required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['siteId'])) {
            throw new ClientException('siteId required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.promotion.common.get', [
            'promotionCodeReq' => $params,
        ], $requestAsync);
    }

    /**
     * 社交媒体获取推广链接接口【申请】
     * materialId   String  是   https://wqitem.jd.com/item/view?sku=23484023378 推广物料链接，建议链接使用微Q前缀，能较好适配微信手Q页面   
     * subUnionId  String  否   618_18_c35***e6a    子联盟ID（需要联系运营开通权限才能拿到数据）
     * positionId  long    否   6   推广位ID
     * pid String  否   618_618_6018    子帐号身份标识，格式为子站长ID_子站长网站ID_子站长推广位ID
     * couponUrl   String  否   http://coupon.jd.com/ilink/get/get_coupon.action?XXXXXXX    优惠券领取链接，在使用优惠券、商品二合一功能时入参，且materialId须为商品详情页链接
     * chainType   int 否   1   转链类型，1：长链， 2 ：短链 ，3： 长链+短链，默认短链
     * giftCouponKey   String  否   xxx_coupon_key  礼金批次号
     */
    public function promotionBysubunionidGet(array $params = [], $requestAsync = false)
    {
        if (!isset($params['materialId'])) {
            throw new ClientException('materialId required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.promotion.bysubunionid.get', [
            'promotionCodeReq' => $params,
        ], $requestAsync);
    }

    /**
     * 工具商获取推广链接接口【申请】
     * materialId   String  是   https://wqitem.jd.com/item/view?sku=23484023378 推广物料链接，建议链接使用微Q前缀，能较好适配微信手Q页面   
     * subUnionId  String  否   618_18_c35***e6a    子联盟ID（需要联系运营开通权限才能拿到数据）
     * positionId  long    否   6   推广位ID
     * pid String  否   618_618_6018    子帐号身份标识，格式为子站长ID_子站长网站ID_子站长推广位ID
     * couponUrl   String  否   http://coupon.jd.com/ilink/get/get_coupon.action?XXXXXXX    优惠券领取链接，在使用优惠券、商品二合一功能时入参，且materialId须为商品详情页链接
     * chainType   int 否   1   转链类型，1：长链， 2 ：短链 ，3： 长链+短链，默认短链
     * giftCouponKey   String  否   xxx_coupon_key  礼金批次号
     */
    public function promotionBysubunionidGet(array $params = [], $requestAsync = false)
    {
        if (!isset($params['materialId'])) {
            throw new ClientException('materialId required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('jd.union.open.promotion.bysubunionid.get', [
            'promotionCodeReq' => $params,
        ], $requestAsync);
    }
}