<?php
/**
 * 采购订单.
 *
 */
use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\ProductDao;

include_once "../Templates/include.php";
//
$productDao = new ProductDao(JxcConfig::$DB_Config);
$w2Products = $productDao->w2uiSelectAll();
$pdt_list = array();
foreach ($w2Products as $v) {
    $w2ValRecId = $v['pdt_id'];;
    $pdt_list[] = $w2ValRecId;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        $().data("jxc_products", <?=json_encode($w2Products)?>);
//        console.log($(document).data("xx"));
//        console.log(this);
//        console.log($(document));

        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '采购',
            multiSelect: true,
            url: {
                'save': 'Jxc/do.php?api=product&c=procure'
            },
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
                    style: 'text-align:center',
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
                {field: 'pdt_name', caption: '名称', size: '10%', style: 'text-align:center'},
                {
                    field: 'pdt_color', caption: '颜色', size: '80px',
                    render: function (record, index, col_index) {
                        var html = this.getCellValue(index, col_index);
                        if (cacheOfColors[html]) {
                            var vc = cacheOfColors[html];
                            return '<div style="height:24px;text-align:center;background-color: #' + vc.color_rgba + ';">' + ' ' + vc.color_name + '</div>';
                        }
                        return '<div>' + html + '</div>';
                    }
                },
                <?php
                // {field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'text'}},
                    $array = array( '3XS', '2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL' );
                    foreach ($array as $k => $v) {
                        echo "{field: 'pdt_count_{$k}', caption: '{$v}', size: '5%', editable: {type: 'text'}},";
                    }
                ?>
                {field: 'pdt_zk', caption: '折扣', size: '7%', render: 'percent', editable: {type: 'percent', min: 0, max: 100}},
                {field: 'pdt_price', caption: '进价', size: '7%', render: 'float:2', editable:{type:'int'}},
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
                                    var postData ={
                                        'changes' : grid.getChanges(),
                                        'op_id' : 1,
                                    };
                                    var ajaxOptions = {
                                        type     : 'POST',
                                        url      : 'Jxc/do.php?api=product&c=procure',
                                        data     : postData,
                                        dataType : 'JSON'
                                    };
                                    $.ajax(ajaxOptions)
                                        .done(function (data, status, xhr) {
                                            if (data.status != 'success') {
                                                w2alert(data.message, "Error");
                                            } else {
                                                grid.mergeChanges();
                                                console.log(data);
                                            }
                                        })
                                        .fail(function (xhr, status, error) {
                                            w2alert('提交订单失败:[' + data.msg + ']', "Error");
                                        });
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
//                        var info = $().data("jxc_products")[v.text];
                        var info = <?=json_encode($w2Products)?>[v.text];
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
        w2GridAddEmptyRecord(content);
        w2ui['layout'].content('main', content);
    });
</script>
</html>