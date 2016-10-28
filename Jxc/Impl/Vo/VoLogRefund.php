<?php
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2016/10/26
 * Time: 22:29
 */

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * 退货记录
 * Class VoLogRefund
 * @package Jxc\Impl\Vo
 */
class VoLogRefund extends Vo {
    public $id;
    public $pdt_id;     //  货号
    public $ct_id;      //  消费者
    public $pdt_counts; //  数量
    public $pdt_price;  //  单价
    public $pdt_zk;     //  折扣

    public function dataArray($fields = array()) {

    }

    public function convert() {

    }

}