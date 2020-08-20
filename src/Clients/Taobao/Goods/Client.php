<?php

namespace Young\Union\Clients\Taobao\Goods;

use Young\Union\Clients\Taobao\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 精选商品，适用于自己推广的媒体使用
     * page_size    Number  false   20  页大小，默认20，1~100
     * adzone_id   Number  true    123 mm_xxx_xxx_xxx的第三位
     * page_no Number  false   1   第几页，默认：1
     * material_id Number  true    123 官方的物料Id(详细物料id见：https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10628875?_k=gpov9a)
     * device_value    String  false   xxx 智能匹配-设备号加密后的值（MD5加密需32位小写），类型为OAID时传原始OAID值
     * device_encrypt  String  false   MD5 智能匹配-设备号加密类型：MD5，类型为OAID时不传
     * device_type String  false   IMEI    智能匹配-设备号类型：IMEI，或者IDFA，或者UTDID（UTDID不支持MD5加密），或者OAID
     * content_id  Number  false   323 内容专用-内容详情ID
     * content_source  String  false   xxx 内容专用-内容渠道信息
     * item_id Number  false   33243   商品ID，用于相似商品推荐
     * favorites_id    String  false   123445  选品库投放id
     * @return [type] [description]
     */
    public function recommend(array $params = [], $requestAsync = false)
    {
        $params = array_merge([
            'adzone_id' => $this->app->config->get('adzone_id')
        ], $params);

        if (!isset($params['adzone_id'])) {
            throw new ClientException('adzone_id required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['material_id'])) {
            throw new ClientException('material_id required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.dg.optimus.material', $params, $requestAsync);
    }

    /**
     * 搜索商品
     * start_dsr    Number  false   10  商品筛选(特定媒体支持)-店铺dsr评分。筛选大于等于当前设置的店铺dsr评分的商品0-50000之间
     * page_size   Number  false   20  页大小，默认20，1~100
     * page_no Number  false   1   第几页，默认：１
     * platform    Number  false   1   链接形式：1：PC，2：无线，默认：１
     * end_tk_rate Number  false   1234    商品筛选-淘客佣金比率上限。如：1234表示12.34%
     * start_tk_rate   Number  false   1234    商品筛选-淘客佣金比率下限。如：1234表示12.34%
     * end_price   Number  false   10  商品筛选-折扣价范围上限。单位：元
     * start_price Number  false   10  商品筛选-折扣价范围下限。单位：元
     * is_overseas Boolean false   false   商品筛选-是否海外商品。true表示属于海外商品，false或不设置表示不限
     * is_tmall    Boolean false   false   商品筛选-是否天猫商品。true表示属于天猫商品，false或不设置表示不限
     * sort    String  false   tk_rate_des 排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi），价格（price）
     * itemloc String  false   杭州  商品筛选-所在地
     * cat String  false   16,18   商品筛选-后台类目ID。用,分割，最大10个，该ID可以通过taobao.itemcats.get接口获取到
     * q   String  false   女装  商品筛选-查询词
     * material_id Number  false   2836    不传时默认物料id=2836；如果直接对消费者投放，可使用官方个性化算法优化的搜索物料id=17004
     * has_coupon  Boolean false   false   优惠券筛选-是否有优惠券。true表示该商品有优惠券，false或不设置表示不限
     * ip  String  false   13.2.33.4   ip参数影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
     * adzone_id   Number  true    12345678    mm_xxx_xxx_12345678三段式的最后一段数字
     * need_free_shipment  Boolean false   true    商品筛选-是否包邮。true表示包邮，false或不设置表示不限
     * need_prepay Boolean false   true    商品筛选-是否加入消费者保障。true表示加入，false或不设置表示不限
     * include_pay_rate_30 Boolean false   true    商品筛选(特定媒体支持)-成交转化是否高于行业均值。True表示大于等于，false或不设置表示不限
     * include_good_rate   Boolean false   true    商品筛选-好评率是否高于行业均值。True表示大于等于，false或不设置表示不限
     * include_rfd_rate    Boolean false   true    商品筛选(特定媒体支持)-退款率是否低于行业均值。True表示大于等于，false或不设置表示不限
     * npx_level   Number  false   2   商品筛选-牛皮癣程度。取值：1不限，2无，3轻微
     * end_ka_tk_rate  Number  false   1234    商品筛选-KA媒体淘客佣金比率上限。如：1234表示12.34%
     * start_ka_tk_rate    Number  false   1234    商品筛选-KA媒体淘客佣金比率下限。如：1234表示12.34%
     * device_encrypt  String  false   MD5 智能匹配-设备号加密类型：MD5
     * device_value    String  false   xxx 智能匹配-设备号加密后的值（MD5加密需32位小写）
     * device_type String  false   IMEI    智能匹配-设备号类型：IMEI，或者IDFA，或者UTDID（UTDID不支持MD5加密），或者OAID
     * lock_rate_end_time  Number  false   1567440000000   锁佣结束时间
     * lock_rate_start_time    Number  false   1567440000000   锁佣开始时间
     * longitude   String  false   121.473701  本地化业务入参-LBS信息-经度
     * latitude    String  false   31.230370   本地化业务入参-LBS信息-纬度
     * city_code   String  false   310000  本地化业务入参-LBS信息-国标城市码，仅支持单个请求，请求饿了么卡券物料时，该字段必填。 （详细城市ID见：https://mo.m.taobao.com/page_2020010315120200508）
     * seller_ids  String  false   1,2,3,4 商家id，仅支持饿了么卡券商家ID，支持批量请求1-100以内，多个商家ID使用英文逗号分隔
     * special_id  String  false   2323    会员运营ID
     * relation_id String  false   3243    渠道关系ID，仅适用于渠道推广场景
     */
    public function search(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['adzone_id']) ) {
            throw new ClientException('adzone_id required', SDK::INVALID_ARGUMENT);
        }

        if ( !isset($params['q']) && !isset($params['cat']) ) {
            throw new ClientException('q or cat required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.dg.material.optional', $params, $requestAsync);
    }

    /**
     * 商品详情 淘宝客商品详情查询(简版)
     * num_iids String  true    123,456 商品ID串，用,分割，最大40个
     * platform    Number  false   1   链接形式：1：PC，2：无线，默认：１
     * ip  String  false   11.22.33.43 ip地址，影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
     */
    public function detail(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['num_iids']) ) {
            throw new ClientException('num_iids required', SDK::INVALID_ARGUMENT);
        }

        if ( is_array($params['num_iids']) ) {
            $params['num_iids'] = implode(',', $params['num_iids']);
        }

        return $this->send('taobao.tbk.item.info.get', $params, $requestAsync);
    }

    /**
     * 链接解析出商品id
     * click_url    String  true    https://s.click.taobao.com/***  长链接或短链接
     */
    public function extract(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['click_url']) ) {
            throw new ClientException('click_url required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.item.click.extract', $params, $requestAsync);
    }

    /**
     * 关联商品查询
     * fields   String  true    num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url    需返回的字段列表
     * num_iid Number  true    123 商品Id
     * count   Number  false   20  返回数量，默认20，最大值40
     * platform    Number  false   1   链接形式：1：PC，2：无线，默认：１
     */
    public function relation(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['fields']) ) {
            throw new ClientException('fields required', SDK::INVALID_ARGUMENT);
        }

        if ( !isset($params['num_iid']) ) {
            throw new ClientException('num_iid required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.item.recommend.get', $params, $requestAsync);
    }

    /**
     * 商品出词API，提供搜索结果页。
     * item_id  Number  true    556550791715    商品id，也有可能查询不到词
     * adzone_id   Number  false   1222    推广位
     * count   Number  false   5   期望获得词数量
     */
    public function word(array $params = [], $requestAsync = false)
    {
        if ( !isset($params['item_id']) ) {
            throw new ClientException('item_id required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.item.word.get', $params, $requestAsync);
    }
}