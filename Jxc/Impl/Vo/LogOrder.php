<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * 订单
 */
final class LogOrder extends Vo {

    public $order_id;   //  订单号
    public $type;       //  订单类型    见JxcConst::IO_TYPE_PROCURE
    public $status;     //  订单状态
    public $log_date;   //  订单日期    -   年月日
    public $ct_id;      //  客户ID
    public $ct_name;    //  客户名称
    public $total_rmb;  //  订单涉及总金额
    public $comment;    //  订单备注
    public $lastUpdateTime;  //  最后一次修改时间
    //  审核
    public $op_id;      //  经办人ID
    public $op_name;    //  经办人名称
    public $step;       //  流程步骤
    public $completed;  //  订单最终状态(取消/完成)不可修改

}