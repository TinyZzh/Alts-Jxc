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
    <script src="../js/w2ui-1.4.3.min.js" type="text/javascript"></script>

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
            header: 'List of Names',
            url: "../Jxc/index.php?api=log_sales",
            columns: [
                {field: 'fname', caption: 'First Name', size: '30%', editable: {type: 'fname',}},
                {field: 'lname', caption: 'Last Name', size: '30%', editable: {type: 'lname',}},
                {field: 'email', caption: 'Email', size: '40%', editable: {type: 'email',}},
                {field: 'sdate', caption: 'Start Date', size: '120px', editable: {type: 'sdate',}}
            ],
            show: {
                toolbar: true,
                toolbarAdd: true,
                toolbarSave: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {type: 'button', id: 'mybutton', caption: 'My other button', img: 'icon-folder'}
                ],
                onClick: function (target, data) {
                    console.log(target);
                }
            },
        });
        w2ui['layout'].content(
            'main',
            content
        );

//        w2ui['div_right'].click(function(a, b){
//            console.log(a);
//        });

    })
    ;
    $(function () {

    });
</script>
</html>