<?php
/**
 * 产品销售管理.
 *
 */
include_once "../Templates/include.php";

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Vo\VoProduct;

$dao = new CustomerDao(JxcConfig::$DB_Config);
$resultSet = $dao->selectCustomNameList();
$custom_list = array();
foreach ($resultSet as $k => $v) {
    $custom_list[] = array('id' => $k, 'text' => $v['ct_name']);
}
$pub_custom_list = json_encode($custom_list);
//

$productDao = new ProductDao(JxcConfig::$DB_Config);
$products = $productDao->selectAll();

$map = array();
$pdt_list = array();
foreach ($products as $k => $v) {
    if ($v instanceof VoProduct) {
        $map[$v->pdt_id] = $v->voToW2ui($v);
        //
        $w2ValRecId = array('id' => $k, 'text' => $v->pdt_id);
        $pdt_list[] = $w2ValRecId;
        $map[$v->pdt_id]->pdt_id = $w2ValRecId;
    }
}

?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        $(document).data("jxc_products", <?=json_encode($map)?>);
        console.log($(document).data("xx"));
        console.log(this);
        console.log($(document));

        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '颜色信息管理',
            multiSelect: true,
            url: {
                'get': 'Jxc/do.php?api=color&c=getColorInfo',
                'save': 'Jxc/do.php?api=color&c=saveColorInfo',
                'remove': 'Jxc/do.php?api=color&c=removeColorInfo'
            },
            columns: [
                {field: 'color_id', caption: '颜色ID', size: '10%', style: 'text-align:center'},
                {
                    field: 'color_rgba', caption: 'RGBA值', size: '80px', editable: {type: 'color'},
                    render: function (record, index, col_index) {
                        var rgba = this.getCellValue(index, col_index);
                        return '<div style="height:24px;text-align:center;background-color: #' + rgba + ';">' + ' ' + rgba + '</div>';
                    }
                },
                {
                    field: 'color_name',
                    caption: '颜色名称',
                    size: '10%',
                    style: 'text-align:center',
                    editable: {type: 'text'}
                }
            ],
            show: {
                header: true,
                toolbar: true,
                toolbarAdd: true,
                toolbarSave: true,
                toolbarDelete: true,
                lineNumbers: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'}
                ]
            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);

        for (var i = 0; i < 2; i++)
            w2GridAddEmptyRecord(content);
    });
</script>
</html>