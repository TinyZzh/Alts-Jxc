<?php
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2016/10/26
 * Time: 22:29
 */

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Libs\W2UI;

/**
 * 产品库存信息
 * Class VoProduct
 * @package Jxc\Impl\Vo
 */
class VoProduct extends Vo {

    public $pdt_id;      //  货号 -   唯一ID
    public $pdt_name;    //  货号名称
    public $pdt_color;   //  颜色
    public $pdt_counts;  //  库存 array
    public $pdt_price;   //  进货单价
    public $pdt_total;   //  库存总数
    public $total_rmb;   //  库存总价值

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

    /**
     * @param $vo VoProduct
     * @return mixed
     */
    public function voToW2ui($vo) {
        $vo->recid = $vo->pdt_id;
        foreach ($vo->pdt_counts as $k => $v) {
            $f = 'pdt_count_' . $k;
            $vo->$f = $v;
        }
        unset($vo->pdt_counts);
        return $vo;
    }

    public function w2uiToVo($data) {
        $fields = array_keys($data);
        foreach ($fields as $k => $fieldName) {
            if (strpos($fieldName, 'pdt_count_') === 0) {   //  FALSE  不同于 0
                $fields[] = "pdt_counts";
                $var = substr($fieldName, strlen('pdt_count_'));
                if (is_numeric($var))
                    $this->pdt_counts[$var] = $data[$fieldName];
                unset($fields[$k]);
            } else if (isset($this->$fieldName) || property_exists($this, $fieldName)) {
                if (is_array($data[$fieldName]) || is_object($data[$fieldName])) {
                    $this->$fieldName = $data[$fieldName]['text'];
                } else
                    $this->$fieldName = $data[$fieldName];
            } else {
//no-op
            }
        }
        $this->pdt_total = $this->calc_pdt_total(); //  总数
        $this->total_rmb = $this->calc_total_price();    //  总价
    }

    private function calc_total_price() {
        return $this->pdt_total * $this->pdt_price;
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