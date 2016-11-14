<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\DateUtil;

/**
 * 采购进货记录
 * Class VoLogSales
 * @package Jxc\Impl\Vo
 */
class LogProcureOrder extends Vo {

    public $order;      //  订单号
    public $datetime;   //  日期
    public $ct_id;      //  消费者
    public $total_rmb;  //  总金额

}