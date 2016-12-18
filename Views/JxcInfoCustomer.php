<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    var configJxc = {
        header: '客户信息管理',
        url: {
            'get': 'Jxc/do.php?api=custom&c=w2GetRecords',
            'save': 'Jxc/do.php?api=custom&c=saveCustomerInfo',
            'remove': 'Jxc/do.php?api=custom&c=removeCustomerInfo'
        },
        columns: [
            {field: 'ct_id', caption: '客户ID', size: '5%', style: 'text-align:center'},
            {
                field: 'ct_name', caption: '客户姓名', size: '7%', style: 'text-align:center',
                searchable: true, editable: {type: 'text'}
            },
            {
                field: 'ct_address', caption: '通信地址', size: '25%', style: 'text-align:right',
                searchable: true, editable: {type: 'text'}
            },
            {field: 'ct_phone', caption: '联系电话', size: '8%', style: 'text-align:right', editable: {type: 'text'}},
            {field: 'ct_money', caption: '账户余额', size: '7%', editable: {type: 'float'}, render: 'money:2'}
        ]
    };
</script>
<?php
include_once "../Templates/TplJxcInfo.php";
?>
</html>