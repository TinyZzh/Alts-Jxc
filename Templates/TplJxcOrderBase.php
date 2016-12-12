<?php
/**
 * 销售订单.
 *
 */
use Jxc\Impl\Core\JxcConst;

include_once "../Templates/include.php";

?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    var toolbar_items = [
        {type: 'break'},
        {
            type: 'button', id: 'btn_save_sales_order', caption: '保存', icon: 'w2ui-icon-check',
            onClick: function (event) {
                console.log(event);
                var grid = w2ui['div_main_cnt'];
                var pdt_id = checkRepeatedField(grid, 0);
                if (pdt_id) {
                    w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                    return;
                }
                if (grid.getChanges().length <= 0) {
                    w2alert("[Msg]数据没有变更，不需要保存.", "Message");
                    return;
                }
                if (configJxc.isSales) {
                    var label_custom_id = this.get('label_custom_id');
                    if (!label_custom_id.caption) {
                        w2alert("[Error]请选择客户.", "Error");
                        return;
                    }
                }
                w2confirm("是否确定提交?", "确认提示框")
                    .yes(function () {
                        var postData = {
                            'changes': grid.getChanges(),
                            'ct_id': label_custom_id.caption
                        };
                        W2Util.request(configJxc.url_popup_submit, postData, function (data, status, xhr) {
                            if (data.status != 'success') {
                                w2alert(data.msg, "Error");
                            } else {
                                console.log(data);
                            }
                        });
                    });
            }
        },
        {type: 'break'}
    ];
    if (configJxc.toolbar && configJxc.toolbar.items.length > 0) {
        toolbar_items = toolbar_items.concat(configJxc.toolbar.items);
    }

    $(document).ready(function () {
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: configJxc.header,
            multiSelect: true,
            columnGroups: [
                {caption: '产品', span: 2},
                {caption: '颜色', master: true},
                {caption: '尺码', span: 9},
                {caption: '标价', span: 2},
                {caption: '总计', span: 2},
                {caption: '备注', master: true}
            ],
            columns: [
                {field: 'pdt_id', caption: '编号', size: '7%', style: 'text-align:center', editable: {type: 'text'}},
                {field: 'pdt_name', caption: '名称', size: '10%', style: 'text-align:center'},
                {field: 'pdt_color', caption: '颜色', size: '80px', render: W2Util.renderJxcColorCell},
                <?php
                // {field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                $array = array('3XS', '2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL');
                foreach ($array as $k => $v) {
                    if ($type == JxcConst::IO_TYPE_SALES) {
                        echo "{field: 'pdt_count_{$k}', caption: '{$v}', size: '55px',style:'text-align:center', editable: {type: 'text'}, render: W2Util.renderJxcPdtSizeCell},";
                    } else {
                        echo "{field: 'pdt_count_{$k}', caption: '{$v}', size: '55px',style:'text-align:center', editable: {type: 'text'}},";
                    }
                }
                ?>
                {field: 'pdt_zk', caption: '折扣', size: '80px', editable: {type: 'percent', min: 0, max: 100}, render: 'percent'},
                {field: 'pdt_price', caption: '单价', size: '80px', render: 'money:2', editable: {type: 'float'}},
                {field: 'pdt_total', caption: '总数量', size: '80px', style: 'text-align:center'},
                {field: 'total_rmb', caption: '总价', size: '100px', render: 'money:2'},
                {field: 'pdt_comment', caption: '备注', size: '15%', editable: {type: 'text'}}
            ],
            show: {
                header: true, toolbar: true, toolbarAdd: true, toolbarDelete: true, lineNumbers: true, footer: true
            },
            toolbar: {items: toolbar_items},
            onEditField: function (event) {
                console.log(event);
                var that = this;
                var column = that.columns[event.column];
                var record = that.records[event.index];
                if ((column.field == 'pdt_id')
                    || (record && record.pdt_id == '')) {
                    event.preventDefault();
                    $.getJSON(configJxc.url_popup_pdt, null, function (data) {
                        if (data['status'] == 'success') {
                            var pdtOptions = popupPdtOption(that, event.index, 0, 'pop_w2grid_pdt', data['records']);
                            PopupUtil.onPopupShow({
                                width: 910,
                                height: 500,
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
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2uiEmptyColumn(content, 1);
        w2ui['layout'].content('main', content);
    });
</script>
</html>