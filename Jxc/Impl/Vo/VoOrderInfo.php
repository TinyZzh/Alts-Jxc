<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;

/**
 * 订单基础信息
 */
class VoOrderInfo extends Vo {

    public $order_id;   //  订单号
    public $type;       //  订单类型    见JxcConst::IO_TYPE_PROCURE
    public $status;     //  订单状态
    public $log_date;   //  订单日期    -   年月日
    public $ct_id;      //  客户ID
    public $ct_name;    //  客户名称
    public $total_rmb;  //  订单涉及总金额
    public $comment;    //  订单备注
    public $datetime;   //  最后一次修改时间

}