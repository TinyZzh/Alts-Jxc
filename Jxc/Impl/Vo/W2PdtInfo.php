<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\W2UI;

/**
 * w2ui 产品信息 -  用于过度运算
 */
class W2PdtInfo extends Vo {

    public $recid;       //  w2grid - recid
    public $pdt_id;      //  货号 -   唯一ID
    public $pdt_counts;  //  数量 array
    public $pdt_zk;      //  折扣
    public $pdt_price;   //  单价

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
        $this->pdt_counts = explode("|", $this->pdt_counts);
    }

    /**
     * vo转换成w2ui数据
     */
    public function voToW2ui() {
        return W2UI::objToW2ui($this);
    }

    /**
     * w2ui数据转换成vo
     * @param $data
     */
    public function w2uiToVo($data) {
        W2UI::w2uiToObj($this, $data);
    }

    /**
     * 产品涉及总价值
     */
    public function calc_total_price() {
        return $this->calc_pdt_total() * ($this->pdt_zk / 100.0) * $this->pdt_price;
    }

    /**
     * 产品涉及总数量
     */
    public function calc_pdt_total() {
        $total = 0;
        foreach ($this->pdt_counts as $count) {
            if ($count)
                $total += $count;
        }
        return $total;
    }
}