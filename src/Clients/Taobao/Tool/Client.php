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
}