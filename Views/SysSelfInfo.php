<?php
/**
 *  操作员个人信息管理
 */
include_once "../Templates/include.php";


?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
</head>
<body id="body">
<div id="layout">
    <div id="div_self_info"></div>
</div>
<script>
    $(document).ready(function () {
        var componentName = 'div_self_info';
        if (!w2ui[componentName]) {
            var formOptions = {
                header: '个 人 信 息',
                name: componentName,
                recid: 1,
                url: {
                    'get': 'Jxc/do.php?api=operator&c=w2GetSelfOperator',
                    'save': 'Jxc/do.php?api=operator&c=w2OpChangeSelfInfo'
                },
                fields: [
                    {name: 'op_id', type: 'int', html: {caption: 'OP ID', attr: 'size="5" readonly'}},
                    {name: 'op_account', type: 'text', html: {caption: '登录账户', attr: 'size="20" maxlength="20"'}},
                    {name: 'op_name', type: 'text', html: {caption: '操作员名称', attr: 'size="20" maxlength="20"'}},
                    {name: 'op_phone', type: 'text', html: {caption: '联系方式', attr: 'size="20"'}},
                    {name: 'status', type: 'text', html: {caption: '状态', attr: 'size="10 readonly"'}}
                ],
                actions: {
                    save: function () {
                        this.save();
                    }
                }
            };
            //  content
            $().w2form(formOptions);
        }
        //  reset data
        w2ui[componentName].record = {};
        w2ui[componentName].refresh();
        //  set content
        w2ui['layout'].content('main', w2ui[componentName]);
    });
</script>
</html>