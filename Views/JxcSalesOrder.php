<?php
/**
 * 库存信息.
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
//        $map[$v->pdt_id] = $v->voToW2ui($v);
        $map[$v->pdt_id] = $v;
        //
        $w2ValRecId = array('id' => $k, 'text' => $v->pdt_id);
        $pdt_list[] = $w2ValRecId;
        $map[$v->pdt_id]->pdt_id = $w2ValRecId;
    }
}
$jsonProducts = json_encode($map);
$pdt_list = json_encode($pdt_list);

?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).data("cache_pdt_info", <?=$jsonProducts?>);
    var cacheOfPdtInfo = $(document).data('cache_pdt_info');

    $(document).ready(function () {
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '销售管理',
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
                        items: <?=$pdt_list?>
                    },
                    render: function (record, index, col_index) {
                        var html = this.getCellValue(index, col_index);
//                        console.log(html);
                        return html.text || '';
                    }
                },
                {field: 'pdt_name', caption: '名称', size: '10%', style: 'text-align:center'},
                {field: 'pdt_color', caption: '颜色', size: '5%'},
                {
                    field: 'pdt_count_0',
                    caption: '3XS',
                    size: '5%',
                    editable: {type: 'numeric'},
                    render: renderSizeField
                },
                {field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_2', caption: 'XS', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_3', caption: 'S', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_4', caption: 'M', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_5', caption: 'L', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_6', caption: 'XL', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_7', caption: '2XL', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {field: 'pdt_count_8', caption: '3XL', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                {
                    field: 'pdt_zk',
                    caption: '折扣',
                    size: '7%',
                    render: 'percent',
                    editable: {type: 'percent', min: 0, max: 100}
                },
                {field: 'pdt_price', caption: '进价', size: '7%'},
                {field: 'pdt_total', caption: '总数量', size: '10%'},
                {field: 'total_rmb', caption: '总价', size: '10%'}
            ],
            show: {
                header: true,
                toolbar: true,
                toolbarAdd: true,
                toolbarDelete: true,
                lineNumbers: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'btn_save_sales_order', caption: '保存', icon: 'w2ui-icon-check',
                        onClick: function (event) {
                            console.log(event);
                            console.log(this);
                            var grid = w2ui['div_main_cnt'];
                            var pdt_id = w2GridCheckUniqueID(grid, 'pdt_id');
                            if (pdt_id) {
                                w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                                return;
                            }
                            if (grid.getChanges().length <= 0) {
                                w2alert("[Msg]数据没有变更，不需要保存.", "Message");
                                return;
                            }
                            w2confirm("是否确定提交?", "确认提示框")
                                .yes(function () {
                                    grid.save();
                                    grid.getChanges();


                                });
                        }
                    }
                ]
            },
            onSubmit: function (event) {
                var pdt_id = w2GridCheckUniqueID(this, 'pdt_id');
                if (pdt_id) {
                    event.preventDefault();
                    w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                }
            },
            onChange: function (event) {
                console.log(event);
                var that = this;
                var column = this.columns[event.column];
                if (column.field == "pdt_id") {
                    if (column.editable.type == 'list') {
                        var v = event.value_new;    // example:  {id:1, text:"content"}
                        if (v.text) {
                            var info = cacheOfPdtInfo[v.text];
                            info.recid = typeof(info.pdt_id) == 'object' ? info.pdt_id.text : info.pdt_id;
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
                    }
                } else {
                    if (event.value_new == '') {
                        return;
                    }
                    var record = that.records[event.index];
                    if (record['pdt_id'] == undefined || record['pdt_id'] == '') {
                        w2alert("[Error]请先输入货号.", "Error");
                        return;
                    }
                    var total = 0;
                    var zk = record['pdt_zk'] ? Number(record['pdt_zk']).toFixed(0) : 100;
                    var counts = [];
                    var changes = that.getChanges();
                    for (var k = 0; k < changes.length; k++) {
                        var changeRecord = changes[k];
                        if (changeRecord['recid'] == event.recid) {
                            for (var cField in changeRecord) {
                                if (cField.indexOf('pdt_count_') >= 0) {
                                    counts[cField.substr(10)] = changeRecord[cField];
                                } else if (cField == 'pdt_zk') {
                                    zk = Number(changeRecord[cField]);
                                }
                            }
                            break;
                        }
                    }
                    if (column.field.indexOf('pdt_count_') >= 0) {
                        var tmpIndex = column.field.substr(10);
                        if (cacheOfPdtInfo[event.recid]) {
                            var cache = cacheOfPdtInfo[event.recid];
                            if (Number(event.value_new) > Number(cache.pdt_counts[tmpIndex])) {
                                event.preventDefault();
                                w2alert("[Error]不能超过库存上限.Max : [" + cache.pdt_counts[tmpIndex] + "].", "Error");
                                return;
                            }
                        }
                        counts[tmpIndex] = event.value_new;
                    } else if (column.field == 'pdt_zk') {
                        zk = Number(event.value_new).toFixed(0);
                    }
                    counts.map(function (v) {
                        total += Number(v);
                    });
                    var total_rmb = Number((record['pdt_price'] * zk / 100)).toFixed(2) * total;
                    console.log('xxyy');
                    event.onComplete = function (event) {
                        that.set(record['recid'], {
                            'pdt_total': total,
                            'total_rmb': total_rmb
                        });
                    };
                }
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);

        //  根据货号筛选
        $.getJSON("../Jxc/index.php?api=get_pdt_id_list", null, function (data) {
            if (data['status'] == 'success') {
                var item = {
                    type: 'menu', id: 'selectPdt', caption: '选择货号',
                    items: data['items']
                };
                w2ui['div_frame'].toolbar.add(item);
                w2ui['div_frame'].toolbar.refresh();
            }
        });


    });
</script>
</html>