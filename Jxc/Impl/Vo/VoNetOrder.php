<?php

namespace Jxc\Impl\Vo;

/**
 * [网络版] - 订单信息
 */
class VoNetOrder extends VoOrderInfo {

    //  审核
    public $op_id;      //  经办人ID
    public $op_name;    //  经办人名称
    public $step;       //  流程步骤
    public $completed;  //  订单最终状态(取消/完成)不可修改

}