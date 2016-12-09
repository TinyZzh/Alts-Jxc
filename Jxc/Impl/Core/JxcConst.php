<?php
namespace Jxc\Impl\Core;

/**
 * Jxc常数
 * Class JxcConfig
 * @package Jxc
 */
final class JxcConst {

    //  进出类型
    const IO_TYPE_PROCURE = 1;  //  采购
    const IO_TYPE_SALES = 2;    //  销售
    const IO_TYPE_REFUND = 3;   //  退货

    //  权限管理信息
    const SYSTEM_AUTHORITY_ALL = 'all_allow';   //  最高授权

    //  状态
    const STATUS_NORMAL = 0;    //  正常
    const STATUS_DESTROY = 1;   //  废弃

    //  订单状态
    const ORDER_STATUS_SUBMIT = 0;      //  已提交(销售)
    const ORDER_STATUS_CONFIRM = 1;     //  确认订单(仓库)
    const ORDER_STATUS_PREPARE = 2;     //  配货中(仓库)
    const ORDER_STATUS_OK = 3;          //  确认发货(销售)
    const ORDER_STATUS_CANCEL = 4;      //  取消发货(销售)
    const ORDER_STATUS_CF_OK = 5;       //  确认发货(仓库)    -   录入物流信息
    const ORDER_STATUS_CF_CANCEL = 6;   //  确认取消发货(仓库)
    const ORDER_STATUS_DESTROY = 7;     //  已废弃

}

