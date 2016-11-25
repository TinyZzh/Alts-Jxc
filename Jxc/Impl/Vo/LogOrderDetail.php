<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Libs\W2UI;

/**
 * 订单详单
 */
class LogOrderDetail extends Vo {

    public $id;
    public $order_id;   //  订单号
    public $type;       //  订单类型
    public $pdt_id;     //  货号
    public $pdt_counts; //  数量  array
    public $pdt_zk;     //  折扣  default: 100
    public $pdt_price;  //  单品单价
    public $pdt_total;  //  总数量
    public $total_rmb;  //  总价

    public function __construct() {
        $this->pdt_zk = 100;
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
        $counts = explode("|", $this->pdt_counts);
        $this->pdt_counts = $counts;
    }

    public function voToW2ui() {
        return W2UI::objToW2ui($this);
    }

    public function w2uiToVo($data) {
        W2UI::w2uiToObj($this, $data);
        $this->pdt_total = $this->calc_pdt_total(); //  总数
        $this->total_rmb = $this->calc_total_price();    //  总价
    }

    private function calc_total_price() {
        return $this->pdt_total * ($this->pdt_zk / 100.0) * $this->pdt_price;
    }

    private function calc_pdt_total() {
        $total = 0;
        foreach ($this->pdt_counts as $count) {
            if ($count)
                $total += $count;
        }
        return $total;
    }
}