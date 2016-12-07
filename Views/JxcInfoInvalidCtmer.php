<?php
/**
 * 历史用户.
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
            header: '历史客户管理',
            url: {
                'get': 'Jxc/do.php?api=custom&c=records&status=1',
                'remove': 'Jxc/do.php?api=custom&c=removeCustomerInfo&status=1'
            },
            columns: [
                {field: 'ct_id', caption: '客户ID', size: '5%', style: 'text-align:center'},
                {field: 'ct_name', caption: '客户姓名', size: '7%', style: 'text-align:center'},
                {field: 'ct_address', caption: '通信地址', size: '25%', style: 'text-align:right'},
                {field: 'ct_phone', caption: '联系电话', size: '8%', style: 'text-align:right'},
                {field: 'ct_money', caption: '账户余额', size: '7%', render: 'money:2'}
            ],
            multiSelect: true,
            show: {toolbar: true, header: true, footer: true, lineNumbers: true, toolbarDelete: true}
        });
        w2ui['layout'].content('main', content);
    });
</script>
</html>