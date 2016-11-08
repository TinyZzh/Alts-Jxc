<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\DateUtil;

/**
 * 销售记录
 * Class LogSales
 * @package Jxc\Impl\Vo
 */
class LogSales extends Vo {
    public $id;
    public $pdt_id;     //  货号
    public $ct_id;      //  消费者
    public $pdt_counts; //  数量  array
    public $pdt_price;  //  单价
    public $pdt_zk;     //  折扣
    public $pdt_total;  //  总数量
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
        $counts = explode("|", $this->pdt_counts);
        $this->pdt_counts = $counts;
    }

    public function voToW2ui($vo) {
        $vo->recid = $vo->id;
        foreach ($vo->pdt_counts as $k => $v) {
            $f = 'pdt_count_' . $k;
            $vo->$f = $v;
        }
        unset($vo->id);
        unset($vo->pdt_counts);
        return $vo;
    }

    public function w2uiToVo($data) {
        $fields = array_keys($data);
        foreach ($fields as $k => $fieldName) {
            if (strpos($fieldName, 'pdt_count_') === 0) {   //  FALSE  不同于 0
                $fields[] = "pdt_counts";
                $var = substr($fieldName, 10);
                if (is_numeric($var))
                    $this->pdt_counts[$var] = $data[$fieldName];
                unset($fields[$k]);
            } else if (isset($this->$fieldName) || property_exists($this, $fieldName)) {
                $this->$fieldName = $data[$fieldName];
            } else {
                //no-op
            }
        }
        $this->pdt_total = $this->calc_pdt_total(); //  总数
        $this->total_rmb = $this->calc_total_price();    //  总价
        $this->datetime = DateUtil::makeTime();
    }

    private function calc_total_price() {
        return $this->pdt_total * ($this->pdt_zk / 100) * $this->pdt_price;
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