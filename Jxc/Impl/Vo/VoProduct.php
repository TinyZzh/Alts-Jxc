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

    public function uiCrtPdtColumns() {
        $columns = array();
        $columns[] = array('field' => 'pdt_id', 'caption' => '货号', 'size' => '10%', 'editable' => array('type' => 'pdt_id'));
        $columns[] = W2UI::w2uiColumn('pdt_id', '货号', '10%', W2UI::w2uiEditable());
        $columns[] = W2UI::w2uiColumn('pdt_name', '货名', '10%', W2UI::w2uiEditable());

        /*
         * //{field: 'pdt_id', caption: '货号', size: '10%', editable: {type: 'pdt_id'}},
//{
//field: 'pdt_name', caption: '名称', size: '10%', resizable: true,
//                    editable: {
//                        type: 'list',
//                        showAll: true,
//                        items: <?=$pub_custom_list?>
<!--},-->
<!--render: function (record, index, col_index) {-->
<!--var html = this.getCellValue(index, col_index);-->
<!--//                        console.log(html);-->
<!--return html.text || '';-->
<!--}-->
<!--},-->
<!--{field: 'pdt_color', caption: '颜色', size: '5%', editable: {type: 'pdt_color'}},-->
<!--{field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_2', caption: 'XS', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_3', caption: 'S', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_4', caption: 'M', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_5', caption: 'L', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_6', caption: 'XL', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_7', caption: '2XL', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_count_8', caption: '3XL', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_price', caption: '单价', size: '5%', editable: {type: 'text',}},-->
<!--{field: 'pdt_total', caption: '总数', size: '5%'},-->
<!--{field: 'total_rmb', caption: '总价', size: '10%'}-->
<!--}-->
         *
         * */

        return $columns;
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