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
class LogRefund extends Vo {
    public $id;
    public $pdt_id;     //  货号
    public $ct_id;      //  消费者
    public $pdt_counts; //  数量  array
    public $pdt_price;  //  单价
    public $pdt_zk;     //  折扣
    public $total_rmb;  //  总价
    public $datetime;   //  日期

    public function __construct() {
        $this->pdt_counts = array();
        for ($i = 0; $i < 10; $i++) {
            $this->pdt_counts[] = '';
        }
    }

    public function toArray($fields = array()) {
        $map = parent::toArray($fields);
        $map['pdt_counts'] = implode("|", $this->pdt_counts);
        return $map;
    }

    public function convert($data) {
        parent::convert($data);
        $this->pdt_counts = explode("|", $this->pdt_counts);
    }
}