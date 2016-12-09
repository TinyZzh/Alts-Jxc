<?php
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2016/10/26
 * Time: 22:29
 */

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\W2UI;

/**
 * 产品库存信息
 */
class VoProduct extends Vo {

    public $pdt_id;      //  货号 -   唯一ID
    public $pdt_name;    //  货号名称
    public $pdt_color;   //  颜色
    public $pdt_counts;  //  库存 array
    public $pdt_cost;    //  成本价
    public $pdt_price;   //  标价
    public $pdt_total;   //  库存总数
    public $pdt_comment; //  备注
    public $total_rmb;   //  库存总价值
    public $datetime;    //  添加时间
    public $timeLastOp;  //  最后一次修改时间
    public $flag;        //  [0]有效数据  [1]废弃的

    public function __construct() {
        $this->pdt_counts = array();
        for ($i = 0; $i < 10; $i++) {
            $this->pdt_counts[] = '';
        }
    }

    public function toArray($fields = array()) {
        $map = parent::toArray($fields);
        if (!$fields || in_array('pdt_counts', $fields)) {
            $map['pdt_counts'] = implode("|", $this->pdt_counts);
        }
        return $map;
    }

    public function convert($data) {
        parent::convert($data);
        if (!is_array($this->pdt_counts)) {
            $this->pdt_counts = explode("|", $this->pdt_counts);
        }
    }

    public function voToW2ui() {
        return W2UI::objToW2ui($this);
    }

    public function w2uiToVo($data) {
        W2UI::w2uiToObj($this, $data);
        $this->pdt_total = $this->calc_pdt_total();
        $this->total_rmb = $this->calc_total_price();
    }

    public function calc_total_price() {
        return $this->calc_pdt_total() * $this->pdt_price;
    }

    public function calc_pdt_total() {
        $total = 0;
        foreach ($this->pdt_counts as $count) {
            if ($count)
                $total += $count;
        }
        return $total;
    }
}