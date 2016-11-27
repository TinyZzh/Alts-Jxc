<?php

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\ColorDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Vo\VoProduct;

//  颜色缓存
$colorDao = new ColorDao(JxcConfig::$DB_Config);
$cacheOfColors = $colorDao->selectAll();
$menuOfColors = $colorDao->w2uiSelectAll();

//  产品信息缓存
$productDao = new ProductDao(JxcConfig::$DB_Config);
$products = $productDao->selectAll();
$cacheOfPdtInfo = array();
$pdt_list = array();
foreach ($products as $k => $v) {
    if ($v instanceof VoProduct) {
        $cacheOfPdtInfo[$v->pdt_id] = $v;
        $w2ValRecId = array('id' => $k, 'text' => $v->pdt_id);
        $pdt_list[] = $w2ValRecId;
        $cacheOfPdtInfo[$v->pdt_id]->pdt_id = $w2ValRecId;
    }
}


?>
<script>
    var cacheOfColors = <?=json_encode($cacheOfColors)?>;
    var menuOfColors = <?=json_encode($menuOfColors)?>;
    console.log(menuOfColors);
    var cacheOfPdtInfo =<?=json_encode($cacheOfPdtInfo)?>;

    $(document).data('api', <?=json_encode(JxcConfig::$SIDEBAR)?>);


    $(document).ready(function () {
        var div_left = $('#div_left').w2sidebar({
            name: 'div_left',
            img: null,
            nodes: [
                {
                    id: 'jxc_info_manage', text: '信息管理', img: 'icon-page',
                    expended: true,
                    group: true,
                    nodes: [
                        {id: 'jxc_info_product', text: '商品信息', img: 'icon-columns'},
                        {id: 'jxc_info_custom', text: '客户信息', img: 'icon-bullet-black'},
                        {id: 'jxc_info_color', text: '颜色信息', img: 'icon-bullet-black'},
                        {id: 'jxc_info_size', text: '尺码信息', img: 'icon-page'},
                        {id: 'jxc_info_pdt_deleted', text: '废弃商品信息', img: 'icon-page'}
                    ]
                },
                {
                    id: 'jxc_procure_manage', text: '产品管理', img: 'icon-folder', expended: true,
                    group: true,
                    nodes: [
                        {id: 'jxc_procure', text: '采购', img: 'icon-page'},
                        {id: 'jxc_sales', text: '销售', img: 'icon-page'},
                        {id: 'jxc_refund', text: '退货', img: 'icon-page'},
                        {id: 'jxc_log_procure', text: '采购日志', img: 'icon-page'},
                        {id: 'jxc_log_sales', text: '销售日志', img: 'icon-page'},
                        {id: 'jxc_log_refund', text: '退货日志', img: 'icon-page'}
                    ]
                },
                {
                    id: 'jxc_store_manage', text: '库存管理', img: 'icon-folder', expanded: true, expended: true,
                    group: true,
                    nodes: [
                        {id: 'jxc_store_show', text: '库存信息', img: 'icon-page'},
                        {id: 'jxc_store_info', text: '管理库存', img: 'icon-page'},
                    ]
                },
            ],
            onClick: function (event) {
                var urls = $(document).data('api');
                var et = event.target;
//                console.log(urls[et]);
                if (urls[et] == undefined || urls[et] == '') {
                    w2alert("UnCompleted API : " + event.target, 'Error');
                    return;
                }
                for (var widget in w2ui) {
                    var nm = w2ui[widget].name;
                    if (['main_layout', 'div_main_cnt'].indexOf(nm) != -1) $().w2destroy(nm);
                }
                w2ui['layout'].load('main', urls[event.target]);
//                console.log(event.target);
            }
        });
        w2ui['layout'].content('left', div_left);
    });
</script>