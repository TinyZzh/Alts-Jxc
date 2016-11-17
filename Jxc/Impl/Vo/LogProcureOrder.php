<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\DateUtil;

/**
 * 采购订单
 * Class VoLogSales
 * @package Jxc\Impl\Vo
 */
class LogProcureOrder extends Vo {

    public $order;      //  订单号
    public $datetime;   //  订单时间
    public $ct_id;      //  客户ID
    public $total_rmb;  //  订单总金额

}