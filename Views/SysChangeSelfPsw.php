<?php
/**
 *  修改密码
 */
include_once "../Templates/include.php";


?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
</head>
<body id="body">
<div id="layout">
    <div id="div_change_psw"></div>
</div>
<script>
    $(document).ready(function () {
        if (!w2ui['div_change_psw']) {
            var formOptions = {
                header: '修 改 密 码',
                name: 'div_change_psw',
                url: {
                    'save': 'Jxc/do.php?api=operator&c=w2OpChangeSelfPsw'
                },
                fields: [
                    { name: 'psw_old', type: 'text', required: true, html: {caption: '旧密码', attr: 'size="40" maxlength="40"'}},
                    { name: 'psw_new', type: 'text', required: true, html: {caption: '新密码', attr: 'size="40" maxlength="40"'}}
                ],
                actions: {
                    "save": function (target, data) {
                        this.save();
                    }
                }
            };
            $("#div_change_psw").w2form(formOptions);
        }
        //  reset record data
        w2ui['div_change_psw'].record = {};
        w2ui['div_change_psw'].refresh();
        //  set content
        w2ui['layout'].content('main', w2ui['div_change_psw']);
    });
</script>
</html>