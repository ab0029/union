<?php

namespace Young\Union\Clients\Taobao\Order;

use Young\Union\Clients\Taobao\Gateway;
use Young\Union\Exceptions\ClientException;
use Young\Union\SDK;

class Client extends Gateway
{
    /**
     * 获取订单
     * 支持参数
     * query_type   Number  false   1   查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
     * position_index  String  false   2222_334666 位点，除第一页之外，都需要传递；前端原样返回。
     * page_size   Number  false   20  页大小，默认20，1~100
     * member_type Number  false   2   推广者角色类型,2:二方，3:三方，不传，表示所有角色
     * tk_status   Number  false   12  淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
     * end_time    String  true    2019-04-23 12:28:22 订单查询结束时间，订单开始时间至订单结束时间，中间时间段日常要求不超过3个小时，但如618、双11、年货节等大促期间预估时间段不可超过20分钟，超过会提示错误，调用时请务必注意时间段的选择，以保证亲能正常调用！
     * start_time  String  true    2019-04-05 12:18:22 订单查询开始时间
     * jump_type   Number  false   1   跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
     * page_no Number  false   1   第几页，默认1，1~100
     * order_scene Number  false   1   场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1
     */
    public function list(array $params = [], $requestAsync = false)
    {
        if (!isset($params['page_no'])) {
            $params['page_no'] = 1;
        }

        if (!isset($params['start_time'])) {
            throw new ClientException('start_time required', SDK::INVALID_ARGUMENT);
        }

        if (!isset($params['end_time'])) {
            throw new ClientException('end_time required', SDK::INVALID_ARGUMENT);
        }

        if ($params['page_no'] > 1 && !isset($params['position_index'])) {
            throw new ClientException('position_index required when page_no > 1', SDK::INVALID_ARGUMENT);
        }

        return $this->send('taobao.tbk.order.details.get', $params, $requestAsync);
    }
}