<?php
/**
 *  操作员信息管理
 */
include_once "../Templates/include.php";


?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
</head>
<body id="body">
<div id="layout_sys_op" style="height: 100%">
    <div id="grid_sys_op"></div>
    <div id="form_sys_op"></div>
</div>
<script>

    var config = {
        sysOpInfoLayout: {
            name: 'layout_sys_op',
            panels: [
                {type: 'left', size: '50%'},
                {type: 'main', size: '50%'}
            ]
        },
        sysOpInfoGrid: {
            name: 'grid_sys_op',
            header: '经办人信息',
            url: {
                'get': 'Jxc/do.php?api=operator&c=w2GetAllOperator'
            },
            columns: [
                {field: 'op_account', caption: '登录名', size: '10%', style: 'text-align:center'},
                {field: 'op_psw', caption: '登录密钥', size: '10%', style: 'text-align:center'},
                {field: 'op_name', caption: '名称', size: '10%', style: 'text-align:center'},
                {field: 'op_phone', caption: '联系方式', size: '10%', style: 'text-align:center'},
                {field: 'op_auth', caption: '操作员权限', size: '10%', style: 'text-align:center'},
                {field: 'status', caption: '状态', size: '10%', style: 'text-align:center', render: W2Util.renderStatus}
            ],
            multiSelect: true,
            show: {
                header: true, toolbar: true, toolbarAdd: true, toolbarSave: true, toolbarDelete: true,
                lineNumbers: true, footer: true, toolbarEdit: true
            },
            onAdd: w2GridOnAdd,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown,
            onClick: function (event) {
                var grid = this;
                var form = w2ui['form_sys_op'];
                console.log(event);
                event.onComplete = function () {
                    var sel = grid.getSelection();
                    console.log(sel);
                    if (sel.length == 1) {
                        form.recid = sel[0];
                        form.record = $.extend(true, {}, grid.get(sel[0]));
                        form.refresh();
                    } else {
                        form.clear();
                    }
                }
            }
        },
        sysOpInfoForm: {
            header: '经办人信息',
            name: 'form_sys_op',
            recid: 1,
            url: {
                'save': 'Jxc/do.php?api=operator&c=w2OpChangeInfo'
            },
            fields: [
                {field: 'op_id', type: 'int', html: {caption: 'OP ID', attr: 'size="5" readonly'}},
                {field: 'op_account', type: 'text', html: {caption: '登录账户', attr: 'size="20" maxlength="20"'}},
                {field: 'op_name', type: 'text', html: {caption: '操作员名称', attr: 'size="20" maxlength="20"'}},
                {field: 'op_phone', type: 'text', html: {caption: '联系方式', attr: 'size="20"'}},
                {
                    field: 'status',
                    type: 'list',
                    options: {items: [
                        {id: 0, text: '正常'},
                        {id: 1, text: '禁止'}
                        ]
                    }
                }
            ],
            actions: {
                save: function () {
                    this.save();
                }
            }
        }
    };


    $(document).ready(function () {
        var layoutName = 'layout_sys_op';
        if (!w2ui[layoutName]) {
            //  initialize layout
            $('layout_sys_op').w2layout(config.sysOpInfoLayout);
            //  left grid
            $('grid_sys_op').w2grid(config.sysOpInfoGrid);
            //  main form
            $('form_sys_op').w2form(config.sysOpInfoForm);

        }
        //  reset data
        w2ui[config.sysOpInfoForm.name].record = {};
//        w2ui[config.sysOpInfoForm.name].refresh();
        //  set content
        w2ui[config.sysOpInfoLayout.name].content('left', w2ui[config.sysOpInfoGrid.name]);
        w2ui[config.sysOpInfoLayout.name].content('main', w2ui[config.sysOpInfoForm.name]);

        w2ui['layout'].content('main', w2ui[config.sysOpInfoLayout.name]);


    });
</script>
</html>