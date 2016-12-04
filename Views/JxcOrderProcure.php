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
        //  先移除popup
//        PopupUtil.onPopupDestroy();

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
                {field: 'pdt_id', caption: '编号', size: '7%', style: 'text-align:center', editable: {type: 'text'}},
                {field: 'pdt_name', caption: '名称', size: '8%', style: 'text-align:center'},
                {field: 'pdt_color', caption: '颜色', size: '80px', render: W2Util.renderJxcColorCell},
                <?php
                // {field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                $array = array('3XS', '2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL');
                foreach ($array as $k => $v)
                    echo "{field: 'pdt_count_{$k}', caption: '{$v}', size: '5%', editable: {type: 'text'}},";
                ?>
                {
                    field: 'pdt_zk',
                    caption: '折扣',
                    size: '7%',
                    render: 'percent',
                    editable: {type: 'percent', min: 0, max: 100}
                },
                {field: 'pdt_price', caption: '单价', size: '7%', render: 'money:2', editable: {type: 'float'}},
                {field: 'pdt_total', caption: '总数量', size: '10%'},
                {field: 'total_rmb', caption: '总价', size: '10%', render: 'money:2'}
            ],
            show: {
                header: true, toolbar: true, toolbarAdd: true, toolbarDelete: true, lineNumbers: true, footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'btn_save_sales_order', caption: '保存', icon: 'w2ui-icon-check',
                        onClick: function (event) {
                            console.log(event);
                            var w2grid = w2ui['div_main_cnt'];
                            var pdt_id = checkRepeatedField(w2grid, 0);
                            if (pdt_id) {
                                w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                                return;
                            }
                            var changes = w2grid.getChanges();
                            if (changes.length <= 0) {
                                w2alert("[Msg]数据没有变更，不需要保存.", "Message");
                                return;
                            }
                            w2confirm("是否确定提交?", "确认提示框")
                                .yes(function () {
                                    var postData = {
                                        'changes': changes,
                                        'op_id': 1,
                                    };
                                    var ajaxOptions = {
                                        type: 'POST',
                                        url: 'Jxc/do.php?api=product&c=procure',
                                        data: postData,
                                        dataType: 'JSON'
                                    };
                                    $.ajax(ajaxOptions)
                                        .done(function (data, status, xhr) {
                                            console.log(data);
                                            if (data.status != 'success') w2alert(data.message, "Error"); else w2grid.mergeChanges();
                                        })
                                        .fail(function (xhr, status, error) {
                                            w2alert('提交订单失败:[' + error.message + ']', "Error");
                                        });
                                });
                        }
                    }
                ]
            },
            onAdd: w2GridOnAdd,
            onEditField: function (event) {
                console.log(event);
                var that = this;
                var column = that.columns[event.column];
                var record = that.records[event.index];
                if ((column.field == 'pdt_id')
                    || (record && record.pdt_id == '')) {
                    event.preventDefault();
                    var url = "Jxc/do.php?api=product&c=pdtW2gridRecords";
                    $.getJSON(url, null, function (data) {
                        if (data['status'] == 'success') {
                            console.log('popup_initialized');
//                            var pdtOptions = popupPdtOption(that, event.index, event.column, 'pop_w2grid_pdt', data['records']);
                            var pdtOptions = popupPdtOption(that, event.index, 0, 'pop_w2grid_pdt', data['records']);
                            PopupUtil.onPopupShow({
                                subOptions: pdtOptions
                            });
                        }
                    });
                }
            },
            onChange: function (event) {
                console.log(event);
                var that = this;
                var column = this.columns[event.column];
                var record = that.records[event.index];
                if (event.value_new == '') {
                    event.preventDefault();
                    return;
                }
                if (record['pdt_id'] == undefined || record['pdt_id'] == '') {
                    w2alert("[Error]请先输入货号.", "Error");
                    return;
                }
                console.log('xxyy');
                event.onComplete = function (evt2) {
                    var total = 0;
                    var price = 0.0;
                    var zk = 100;
                    var counts = [];
                    for (var e = 0; e < that.columns.length; e++) {
                        var col = that.columns[e];
                        var val = that.getCellValue(event.index, e, false);
                        if (col.field.indexOf('pdt_count_') >= 0) {
                            var tmpIndex = col.field.substr(10);
                            counts[tmpIndex] = (event.column == e) ? Number(event.value_new) : val;
                        } else if (col.field == 'pdt_zk') {
                            zk = (event.column == e) ? Number(event.value_new).toFixed(0) : val;
                            if (zk <= 0) zk = 100;
                        } else if (col.field == 'pdt_price') {
                            price = Number(val).toFixed(2);
                        }
                    }
                    counts.map(function (v) {
                        total += Number(v);
                    });
                    var total_rmb = Number((price * zk / 100)).toFixed(2) * total;

                    that.set(record['recid'], {
                        'pdt_total': total,
                        'total_rmb': total_rmb
                    });
                };
            }
        });
        w2GridAddEmptyRecord(content);
        w2ui['layout'].content('main', content);
    });
</script>
</html>