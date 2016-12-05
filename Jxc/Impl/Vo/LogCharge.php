<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * 充值记录日志
 */
class LogCharge extends Vo {

    public $id;         //  唯一ID
    public $ct_id;      //  客户ID
    public $datetime;   //  日期
    public $money;      //  充值金额
    public $operator;   //  操作员

}