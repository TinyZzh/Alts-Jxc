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
        $map[$v->pdt_id] = $v->voToW2ui();
        //
        $w2ValRecId = array('id' => $k, 'text' => $v->pdt_id);
        $pdt_list[] = $w2ValRecId;
        $map[$v->pdt_id] = $w2ValRecId;
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
            header: '客户信息管理',
            multiSelect: true,
            url: {
                'get': 'Jxc/do.php?api=custom&c=getAllCustomerInfo',
                'save': 'Jxc/do.php?api=custom&c=saveCustomerInfo',
                'remove': 'Jxc/do.php?api=custom&c=removeCustomerInfo'
            },
            columns: [
                {field: 'ct_id', caption: '客户ID', size: '5%', style: 'text-align:center'},
                {field: 'ct_name', caption: '客户姓名', size: '7%', style: 'text-align:center', editable: {type: 'text'}},
                {field: 'ct_address', caption: '通信地址', size: '25%', style: 'text-align:right', editable: {type: 'text'}},
                {field: 'ct_phone', caption: '联系电话', size: '8%', style: 'text-align:right', editable: {type: 'text'}},
                {field: 'ct_money', caption: '账户余额', size: '7%', editable: {type: 'float'}, render: 'money:2'}
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
        w2GridAddEmptyRecord(content);
    });
</script>
</html>