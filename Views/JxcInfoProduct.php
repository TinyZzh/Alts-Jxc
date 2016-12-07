<?php
/**
 * 管理产品基本信息.
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '产品信息管理',
            url: {
                'get': 'Jxc/do.php?api=product&c=getPdtList',
                'remove': 'Jxc/do.php?api=product&c=removePdtInfo',
                'save': 'Jxc/do.php?api=product&c=savePdtInfo'
            },
            columns: [
                {field: 'pdt_id', caption: '编号', size: '10%', editable: {type: 'text'}},
                {field: 'pdt_name', caption: '名称', size: '10%', editable: {type: 'text'}},
                {field: 'pdt_color', caption: '颜色', size: '80px', editable: {type: 'text'}, render: W2Util.renderJxcColorCell},
                {field: 'pdt_cost', caption: '成本价', size: '5%', editable: {type: 'float'}, render: 'money:2'},
                {field: 'pdt_price', caption: '进货价', size: '5%', editable: { type: 'float'}, render: 'money:2'},
                {field: 'datetime', caption: '记录时间', size: '5%'}
            ],
            multiSelect: true,
            show: { toolbar: true, toolbarAdd: true, toolbarDelete: true, lineNumbers: true, header: true, footer: true},
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'pdt_save', caption: '保存', icon: 'w2ui-icon-check',
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
                            w2confirm("是否确定提交?", "确认提示").yes(function () {
                                grid.save();
                            });
                        }
                    }
                ]
            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown,
            onEditField: function (event) {
                console.log(event);
                var that = this;
                if (this.columns[event.column].field == 'pdt_color') {  //  编辑单品颜色
                    event.preventDefault();
                    $.getJSON("Jxc/do.php?api=color&c=w2Records", null, function (data) {
                        if (data['status'] == 'success') {
                            var colorsOptions = popupColorsOption(that, event.index, event.column, 'pop_w2grid_colors', data['records']);
                            PopupUtil.onPopupShow({
                                subOptions: colorsOptions
                            });
                        }
                    });
                }
            }
        });
        w2ui['layout'].content('main', content);
    });
</script>
</html>