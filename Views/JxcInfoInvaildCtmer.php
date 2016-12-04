<?php
/**
 * 历史用户管理.
 */
include_once "../Templates/include.php";
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        //  渲染w2grid
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '历史客户管理',
            multiSelect: true,
            url: {
                'get': 'Jxc/do.php?api=product&c=getPdtList&flag=1',
                'remove': 'Jxc/do.php?api=product&c=removePdtInfo&flag=1'
            },
            columns: [
                {field: 'ct_id', caption: '客户ID', size: '5%', style: 'text-align:center'},
                {field: 'ct_name', caption: '客户姓名', size: '7%', style: 'text-align:center', editable: {type: 'text'}},
                {field: 'ct_address', caption: '通信地址', size: '25%', style: 'text-align:right', editable: {type: 'text'}},
                {field: 'ct_phone', caption: '联系电话', size: '8%', style: 'text-align:right', editable: {type: 'text'}},
                {field: 'ct_money', caption: '账户余额', size: '7%', editable: { type: 'float'}, render: 'money:2'}
            ],
            show: {
                toolbar: true,
                header: true,
                footer: true,
                lineNumbers: true,
                toolbarDelete: true
            }
        });
        w2ui['layout'].content('main', content);
    });
</script>
</html>