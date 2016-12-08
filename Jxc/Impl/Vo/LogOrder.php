<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\DateUtil;

/**
 * 订单
 */
class LogOrder extends Vo {

    public $order_id;   //  订单号
    public $type;       //  订单类型    见JxcConst::IO_TYPE_PROCURE
    public $datetime;   //  订单时间
    public $log_date;   //  订单日期
    public $ct_id;      //  客户ID
    public $ct_name;    //  客户名称
    public $total_rmb;  //  订单涉及总金额
    public $op_id;      //  操作员ID
    public $op_name;    //  操作员名称

}