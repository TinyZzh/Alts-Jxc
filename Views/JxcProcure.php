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
            header: '采购',
            multiSelect: true,
            columnGroups: [
                {caption: '产品', span: 2},
                {caption: '颜色', master: true},
                {caption: '尺码', span: 9},
                {caption: '标价', span: 2},
                {caption: '总计', span: 2}
            ],
            columns: [
                {
                    field: 'pdt_id', caption: '编号', size: '10%',
                    editable: {
                        type: 'list',
                        showAll: true,
                        items: <?=json_encode($pdt_list)?>
                    },
                    render: function (record, index, col_index) {
                        var html = this.getCellValue(index, col_index);
//                        console.log(html);
                        return html.text || '';
                    }
                },
                {field: 'pdt_name', caption: '名称', size: '10%', style:'text-align:center'},
                {field: 'pdt_color', caption: '颜色', size: '5%'},
                {field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_2', caption: 'XS', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_3', caption: 'S', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_4', caption: 'M', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_5', caption: 'L', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_6', caption: 'XL', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_7', caption: '2XL', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_count_8', caption: '3XL', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_price', caption: '进价', size: '5%'},
                {field: 'pdt_zk', caption: '折扣', size: '5%'},
                {field: 'pdt_total', caption: '总数量', size: '10%'},
                {field: 'total_rmb', caption: '总价', size: '10%'}
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
                    {type: 'button', id: 'newLogSales', caption: '新增销售记录',},
                    {
                        type: 'menu', id: 'menuPdt', caption: '货号2',
                        items: ["xxxx", "yyyy"],
                        options: {
                            url: "../Jxc/index.php?api=get_pdt_id_list",
                            postData: {
                                'moduleId': 'menuPdt',
                                'aryPdtId': '1'
                            }
                        }
                    }
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
                        'pdt_id':this.records[i]['recid'],
                        'pdt_counts':[],
                        'pdt_zk':this.records[i]['pdt_zk']
                    };
                    for (var j =0;j<10;j++) {
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