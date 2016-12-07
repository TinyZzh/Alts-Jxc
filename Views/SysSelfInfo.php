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
    <div id="div_left"></div>
    <div id="div_right"></div>
</div>
<script>

    $(document).ready(function () {

        var formOptions = {
            header: '个 人 信 息',
            name: 'form',
            url:{
                'get' : 'Jxc/do.php?api=operator&c=getSelfOperator'
            },
            fields: [
                {name: 'op_id', type: 'int', html: {caption: 'OP ID', attr: 'size="10" readonly'}},
                {name: 'op_account', type: 'text', required: true, html: {caption: '登录账户', attr: 'size="40" maxlength="40"'}},
                {name: 'op_name', type: 'text', required: true, html: {caption: '操作员名称', attr: 'size="40" maxlength="40"'}},
                {name: 'op_phone', type: 'text', html: {caption: '联系方式', attr: 'size="30"'}},
                {name: 'status', type: 'text', html: {caption: '状态', attr: 'size="10"'}}
            ],
            actions: {
                save: function () {
                    $.ajax({
                        type: 'GET',
                        url: 'Jxc/do.php?api=order&c=getOrderDetail',
                        data: {},
                        dataType: 'JSON'
                    }).done(function (data, status, xhr) {
                        console.log(data);
                        if (data.status != 'success') {
                            w2alert(data.message, "Error");
                        } else {

                        }
                    }).fail(function (xhr, status, error) {
                        w2alert('HTTP ERROR:[' + error.message + ']', "Error");
                    });
                }
            }
        };
        //  content
        var content = $().w2form(formOptions);
        w2ui['layout'].content('main', content);
    });
</script>
</html>