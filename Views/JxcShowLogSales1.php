<?php
//include_once "../Jxc/Modules/log_sales.php"
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>Index - Title</title>
    <link href="../css/jxc-1.0.0.css" type="text/css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="../css/bootstrap-theme.min.css" type="text/css" rel="stylesheet">
    <link href="../css/w2ui-1.4.3.min.css" type="text/css" rel="stylesheet">

    <script src="../js/jquery.min.js" type="text/javascript"></script>
    <script src="../js/jxc-1.0.0.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/w2ui-1.4.3-zh-cn.js" type="text/javascript"></script>

</head>
<body id="body">
<!--
<nav id="jxc_nav" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <p>导航栏</p>
</nav>
-->

<div id="layout">
    <div id="jxc_nav" class="navbar navbar-default navbar-fixed-top" role="navigation">
        <p>导航栏</p>
    </div>
    <div id="div_left">
        <p>1111</p>
    </div>
    <div id="div_right">
        <p>1111</p>
    </div>
    <div id="div_footer">
        <button type="button" class="btn btn-default">Left</button>
        <p>22222</p>
    </div>
</div>

</body>

<script>
    $(document).ready(function () {
//        $.get("../Jxc/index.php?api=log_sales", function(data) {
//            console.log(data);
//        });

        $('#layout').height($(window).height());
        $('#layout').w2layout({
            name: 'layout',
            panels: [
                {type: 'top', size: 50, content: 'jxc_nav'},
                {type: 'left', size: 200, content: 'div_left'},
//                { type: 'main', style: pstyle, content: 'main' },
//                { type: 'preview', size: '50%', content: 'preview' },
                {type: 'main', size: 200},
                {type: 'bottom', size: 50, content: 'div_footer'}
            ]
        });

        var content = $('#div_right').w2grid({
            name: 'div_frame',
            header: '销售记录',
            multiSelect: true,
            url: "../Jxc/index.php?api=log_sales",
            columns: [
                {field: 'id', caption: '自增ID'},
                {field: 'pdt_id', caption: '货号', size: '10%', editable: {type: 'pdt_id',}},
                {field: 'ct_id', caption: '客户', size: '10%', editable: {type: 'ct_id',}},
                {field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'pdt_count_0',}},
                {field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'pdt_count_1',}},
                {field: 'pdt_count_2', caption: 'XS', size: '5%', editable: {type: 'pdt_count_2',}},
                {field: 'pdt_count_3', caption: 'S', size: '5%', editable: {type: 'pdt_count_3',}},
                {field: 'pdt_count_4', caption: 'M', size: '5%', editable: {type: 'pdt_count_4',}},
                {field: 'pdt_count_5', caption: 'L', size: '5%', editable: {type: 'pdt_count_5',}},
                {field: 'pdt_count_6', caption: 'XL', size: '5%', editable: {type: 'pdt_count_6',}},
                {field: 'pdt_count_7', caption: '2XL', size: '5%', editable: {type: 'pdt_count_7',}},
                {field: 'pdt_count_8', caption: '3XL', size: '5%', editable: {type: 'pdt_count_8',}},
                {field: 'pdt_price', caption: '单价', size: '5%', editable: {type: 'pdt_price',}},
                {field: 'pdt_zk', caption: '折扣', size: '5%', editable: {type: 'pdt_zk',}},
                {field: 'pdt_total', caption: '总数', size: '5%', editable: {type: 'pdt_total',}},
                {field: 'total_rmb', caption: '总价', size: '10%', editable: {type: 'total_rmb',}},
                {field: 'datetime', caption: '订单时间', size: '150px', editable: {type: 'datetime',}}
            ],
            show: {
                toolbar: true,
                toolbarAdd: true,
                toolbarSave: true,
                toolbarDelete: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {type: 'button', id: 'mybutton', caption: 'My other button', img: 'icon-folder'},
                    {type: 'button', id: 'newLogSales', caption: '新增销售记录',},
                    {
                        type: 'menu', id: 'menuPdt', caption: '货号2',
                        items: ["xxxx", "yyyy"],
                        options: {
                            url: "../Jxc/index.php?api=get_pdt_id_list",
                            postData: {
                                'moduleId': 'menuPdt',
                                'aryPdtId': '1'
                            }
                        }
                    }
                ],
                onClick: function (target, data) {
                    console.log(target);
                }
            },
            onAdd: function (event) w2GridAddRecord(this),
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });

        w2ui['layout'].content(
            'main',
            content
        );

        //  根据货号筛选
        $.getJSON("../Jxc/index.php?api=get_pdt_id_list", null, function (data) {
            if (data['status'] == 'success') {
                var item = {
                    type: 'menu', id: 'selectPdt', caption: '选择货号',
                    items: data['items'],

                };
                w2ui['div_frame'].toolbar.add(item);
                w2ui['div_frame'].toolbar.refresh();
            }
        });


        //        w2ui['div_right'].click(function(a, b){
        //            console.log(a);
        //        });

    })
    ;
    $(function () {

    });
</script>
</html>