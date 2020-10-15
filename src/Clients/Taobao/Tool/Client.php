<?php

namespace Young\Union\Clients\Taobao\Tool;

use Young\Union\Clients\Taobao\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 淘宝客-推广者-官方活动转链
     * adzone_id    Number  true    123 mm_xxx_xxx_xxx的第三位
     * sub_pid String  false   mm_1_2_3    mm_xxx_xxx_xxx 仅三方分成场景使用
     * relation_id Number  false   123 渠道关系id
     * activity_material_id    String  true    123 官方活动会场ID，从淘宝客后台“我要推广-活动推广”中获取
     * union_id    String  false   demo    自定义输入串，英文和数字组成，长度不能大于12个字符，区分不同的推广渠道
     */
    public function activityInfoGet(array $params = [], $requestAsync = false) 
    {
        $params = array_merge([
            'adzone_id' => $this->app->config->get('adzone_id')
        ], $params);

        if (!isset($params['adzone_id'])) {
            throw new ClientException('adzone_id required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['activity_material_id'])) {
            throw new ClientException('activity_material_id required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.activity.info.get', $params, $requestAsync);
    }

    /**
     * 淘宝客-公用-淘口令生成
     * user_id  String  false   123 生成口令的淘宝用户ID
     * text    String  true    长度大于5个字符    口令弹框内容
     * url String  true    https://uland.taobao.com/   口令跳转目标页
     * logo    String  false   https://uland.taobao.com/   口令弹框logoURL
     * ext String  false   {}  [已废弃]扩展字段JSON格式
     */
    public function tpwdCreate(array $params = [], $requestAsync = false)
    {
        if (!isset($params['text'])) {
            throw new ClientException('text required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['url'])) {
            throw new ClientException('url required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.tpwd.create', $params, $requestAsync);
    }

    /**
     * 淘宝客-公用-长链转短链
     * requests TbkSpreadRequest[]  true        请求列表，内部包含多个url
     *      url String  true    http://temai.taobao.com 原始url, 只支持uland.taobao.com，s.click.taobao.com， ai.taobao.com，temai.taobao.com的域名转换，否则判错
     */
    public function spreadGet(array $params = [], $requestAsync = false)
    {
        // 例子
        // $requests = [
        //     ['url' => 'xxxxxx'],
        //     ['url' => 'xxxxxx'],
        // ];

        if (!isset($params['requests'])) {
            throw new ClientException('requests required', SDK::INVALID_ARGUMENT);
        }

        if (is_array($params['requests'])) {
            $params['requests'] = \json_encode($params['requests']);
        }

        return $this->send('taobao.tbk.tpwd.create', $params, $requestAsync);
    }

    /**
     * 淘宝客-公用-链接解析出商品id
     * click_url    String  true    https://s.click.taobao.com/***  长链接或短链接
     */
    public function itemClickExtract(array $params = [], $requestAsync = false)
    {
        if (!isset($params['click_url'])) {
            throw new ClientException('click_url required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.item.click.extract', $params, $requestAsync);
    }

    /**
     * 淘宝客-公用-私域用户邀请码生成
     * relation_id  Number  false   11  渠道关系ID
     * relation_app    String  true    common  渠道推广的物料类型
     * code_type   Number  true    1   邀请码类型，1 - 渠道邀请，2 - 渠道裂变，3 -会员邀请
     */
    public function scInvitecodeGet(array $params = [], $requestAsync = false)
    {
        if (!isset($params['relation_app'])) {
            throw new ClientException('relation_app required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['code_type'])) {
            throw new ClientException('code_type required', SDK::INVALID_ARGUMENT);
        }

        $this->checkSessionOrThrow();

        return $this->send('taobao.tbk.sc.invitecode.get', $params, $requestAsync);
    }

    /**
     * 淘宝客-公用-私域用户备案
     * relation_from    String  false   1   渠道备案 - 来源，取链接的来源
     * offline_scene   String  false   1   渠道备案 - 线下场景信息，1 - 门店，2- 学校，3 - 工厂，4 - 其他
     * online_scene    String  false   1   渠道备案 - 线上场景信息，1 - 微信群，2- QQ群，3 - 其他
     * inviter_code    String  true    xxx 淘宝客邀请渠道或会员的邀请码
     * info_type   Number  true    1   类型，必选 默认为1:
     * note    String  false   小蜜蜂 媒体侧渠道备注
     * register_info   String  false   {"phoneNumber":"18801088599","city":"江苏省","province":"南京市","location":"玄武区花园小区","detailAddress":"5号楼3单元101室","shopType":"社区店","shopName":"全家便利店","shopCertifyType":"营业执照","certifyNumber":"111100299001"}   线下备案注册信息,字段包含: 电话号码(phoneNumber，必填),省(province,必填),市(city,必填),区县街道(location,必填),详细地址(detailAddress,必填),经营类型(career,线下个人必填),店铺类型(shopType,线下店铺必填),店铺名称(shopName,线下店铺必填),店铺证书类型(shopCertifyType,线下店铺选填),店铺证书编号(certifyNumber,线下店铺选填)
     */
    public function scPublisherInfoSave(array $params = [], $requestAsync = false)
    {
        if (!isset($params['inviter_code'])) {
            throw new ClientException('inviter_code required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['info_type'])) {
            throw new ClientException('info_type required', SDK::INVALID_ARGUMENT);
        }

        $this->checkSessionOrThrow();

        return $this->send('taobao.tbk.sc.publisher.info.save', $params, $requestAsync);
    }

    /**
     * 淘宝客-公用-私域用户备案信息查询
     * info_type    Number  true    1   类型，必选 1:渠道信息；2:会员信息
     * relation_id Number  false   2323    渠道独占 - 渠道关系ID
     * page_no Number  false   1   第几页
     * page_size   Number  false   10  每页大小
     * relation_app    String  true    common  备案的场景：common（通用备案），etao（一淘备案），minietao（一淘小程序备案），offlineShop（线下门店备案），offlinePerson（线下个人备案）。如不填默认common。查询会员信息只需填写common即可
     * special_id  String  false   1212    会员独占 - 会员运营ID
     * external_id String  false   12345   淘宝客外部用户标记，如自身系统账户ID；微信ID等
     */
    public function scPublisherInfoGet(array $params = [], $requestAsync = false)
    {
        if (!isset($params['info_type'])) {
            throw new ClientException('info_type required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['relation_app'])) {
            throw new ClientException('relation_app required', SDK::INVALID_ARGUMENT);
        }

        $this->checkSessionOrThrow();

        return $this->send('taobao.tbk.sc.publisher.info.get', $params, $requestAsync);
    }
}