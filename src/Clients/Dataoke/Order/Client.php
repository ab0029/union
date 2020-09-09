<?php

namespace Young\Union\Clients\Dataoke\Order;

use Young\Union\Clients\Dataoke\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 获取订单
     * 支持参数
     * queryType Number 否 查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
     * positionIndex String 否 位点，除第一页之外，都需要传递；前端原样返回。 
     * pageSize Number 否 页大小，默认20，1~100 
     * memberType Number 否 推广者角色类型,2:二方，3:三方，不传，表示所有角色 
     * tkStatus Number 否 淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态 
     * endTime String 是 订单查询结束时间，订单开始时间至订单结束时间，中间时间段日常要求不超过3个小时，但如618、双11、年货节等大促期间预估时间段不可超过20分钟，超过会提示错误，调用时请务必注意时间段的选择，以保证亲能正常调用！ 时间格式：YYYY-MM-DD HH:MM:SS 
     * startTime String 是 订单查询开始时间。时间格式：YYYY-MM-DD HH:MM:SS 
     * jumpType Number 否 跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页 
     * pageNo Number 否 第几页，默认1，1~100
     * orderScene Number 否 场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1
     */
    public function list(array $params = [], $requestAsync = false)
    {
        if (!isset($params['version'])) {
            $params['version'] = 'v1.0.0';
        }

        if (!isset($params['startTime'])) {
            throw new ClientException('startTime required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['endTime'])) {
            throw new ClientException('endTime required', SDK::INVALID_ARGUMENT);
        }

        return $this->send('GET', 'api/tb-service/get-order-details', $params, $requestAsync);
    }
}