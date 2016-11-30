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
}

