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
 * 产品库存信息
 * Class VoProduct
 * @package Jxc\Impl\Vo
 */
class VoProduct extends Vo {
    public $id;
    public $pdt_id;         //  产品
    public $pdt_name;       //  货号名称
    public $pdt_color;      //  颜色
    public $pdt_stock;  //  库存
    public $pdt_purchase;   //  进货价

    public function toArray($fields = array()) {
        $map = parent::toArray($fields);
        $map['pdt_stock'] = implode("|", $this->pdt_stock);
        return $map;
    }


    public function convert($data) {
        parent::convert($data);
        $this->pdt_stock = explode("|", $this->pdt_stock);
    }
}