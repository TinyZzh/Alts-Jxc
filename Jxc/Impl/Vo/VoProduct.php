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
class VoProduct extends W2PdtInfo {

    public $pdt_id;      //  货号 -   唯一ID
    public $pdt_name;    //  货号名称
    public $pdt_color;   //  颜色
    public $pdt_counts;  //  库存 array
    public $pdt_price;   //  进货单价
    public $pdt_total;   //  库存总数
    public $total_rmb;   //  库存总价值
    public $datetime;    //  添加时间
    public $timeLastOp;  //  最后一次修改时间
    public $flag;        //  [0]有效数据  [1]废弃的

    public function w2uiToVo($data) {
        parent::w2uiToVo($data);
        $this->pdt_total = parent::calc_pdt_total();
        $this->total_rmb = parent::calc_total_price();
    }
}