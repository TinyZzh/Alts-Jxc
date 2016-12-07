<?php
//  Autoload register
include_once "Jxc/AutoLoader.php";
include_once "Jxc/Impl/Core/JxcConfig.php";
Jxc\AutoLoader::register();
//  HTTP util
//include_once "./Jxc/Impl/Libs/Requests.php";
//Requests::register_autoloader();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="title" content="奥蕾泰森 - 进销存管理系统">
    <title>奥蕾泰森 - 进销存管理系统</title>
    <link href="css/jxc-1.0.0.css" type="text/css" rel="stylesheet">
    <link href="css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" type="text/css" rel="stylesheet">
    <link href="css/w2ui-1.4.3.min.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/w2ui-1.4.3-zh-cn.js" type="text/javascript"></script>
    <script src="js/jxc-1.0.0.js?v=<?= time() ?>" type="text/javascript"></script>

    <script src="http://echarts.baidu.com/dist/echarts.js" type="text/javascript"></script>
    <script src="http://echarts.baidu.com/asset/theme/vintage.js" type="text/javascript"></script>
</head>
<body id="body">
<div id="layout">
    <div id="jxc_nav" class="navbar navbar-default navbar-fixed-top" role="navigation"><p>导航栏</p></div>
    <div id="div_left"></div>
    <div id="div_right"></div>
    <div id="div_footer"></div>
</div>
<script type="text/javascript">
    //  locale
    w2utils.settings.locale = 'zh-cn';
    w2utils.settings.currencyPrefix = '￥';
    w2utils.settings.date_format = "yyyy-mm-dd";
    w2utils.settings.time_format = "hh24:mm:ss";

//    w2utils.settings.dateFormat = "yyyy-mm-dd";
//    w2utils.settings.timeFormat = "hh:mi pm";

    //  initialize layout
    $('#layout').height($(window).height());
    $('#layout').w2layout({
        name: 'layout',
        panels: [
            {type: 'top', size: 50, content: 'jxc_nav'},
            {type: 'left', size: 200, content: 'div_left'},
            {type: 'main', size: 200},
            {type: 'bottom', size: 50, content: 'div_footer'}
        ]
    });
</script>
<?php include_once "./Templates/layout_left.php"; ?>
<?php
//include_once "Views/JxcOrderSales.php";
?>
</body>
</html>