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
            header: '客户信息管理',
            multiSelect: true,
            columns: [
                {field: 'ct_id', caption: '客户ID', size: '10%', style: 'text-align:center', editable: {type: 'text'}},
                {field: 'ct_name', caption: '客户姓名', size: '10%', style: 'text-align:center', editable: {type: 'text'}},
                {field: 'ct_address', caption: '通信地址', size: '10%', style: 'text-align:right', editable: {type: 'text'}},
                {field: 'ct_phone', caption: '联系电话', size: '10%', style: 'text-align:right', editable: {type: 'text'}},
                {field: 'ct_money', caption: '账户余额', size: '10%', style: 'text-align:right',
                    editable: {
                        type: 'float',
                        currencySuffix: '$'
                    },
                    render: function (record) {
                        console.log(record);
                        var money = 0;
                        if (record.changes && record.changes.ct_money) {
                            money = record.changes.ct_money;
                        }
                        return '<div>¥' + ' ' + Number(money).toFixed(2) + '</div>';
                    }
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
                    {type: 'break'},
                    {type: 'button', id: 'mybutton', caption: 'My other button', img: 'icon-folder'},
                    {type: 'button', id: 'newLogSales', caption: '新增销售记录',}
                ],
                onClick: function (target, data) {
                    console.log(target);
                }
            },
            onSubmit: function (event) {
                var pdt_id = w2GridCheckUniqueID(this, 'pdt_id');
                if (pdt_id) {
                    event.preventDefault();
                    w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                }
                console.log('tinyz');
                var request = [];
                for (var i in this.records) {
                    var vo = {
                        'pdt_id': this.records[i]['recid'],
                        'pdt_counts': [],
                        'pdt_zk': this.records[i]['pdt_zk']
                    };
                    for (var j = 0; j < 10; j++) {
                        console.log(this.records[i]['pdt_count' + j]);
                        vo['pdt_counts'].push(this.records[i]['pdt_count_' + j]);
                    }
                    request.push(vo);
                }

                console.log(request);

            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onAdd: w2GridOnAdd,
//            onEditField: w2GridOnEditField,
            onEditField: function (event) {
                console.log(event);
                console.log(event.value);
            },
            onChange: function (event) {
                var that = this;
                var column = this.columns[event.column];
                if (column.field == "pdt_id" && column.editable.type == 'list') {
                    var v = event.value_new;    // example:  {id:1, text:"content"}
                    console.log(v.text);
                    if (v.text) {
                        var info = $(document).data("jxc_products")[v.text];
                        if (info) {
                            //  删除
                            column.editable.items = removeByItemId(column.editable.items, v.id);
                            //  移除空白行
                            for (var i = 0; i < that.records.length; i++) {
                                var firstField = that.columns[0].field;
                                if (that.records[i][firstField] == '') {
                                    that.remove(that.records[i].recid);
                                }
                            }
                            that.remove(event['recid']);
                            that.add(info);
                            w2GridAddEmptyRecord(that);
                        } else {
                            w2alert("[Error]产品信息不存在!", "Error");
                        }
                    }
                    console.log(event);
                }

            },
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);

        for (var i = 0; i < 2; i++)
            w2GridAddEmptyRecord(content);

    });
</script>
</html>