<?php
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['op_id']) || !isset($_SESSION['op_name'])) {
    header("Location: login.php");
    exit;
}
$op_id = $_SESSION['op_id'];
$op_name = isset($_SESSION['op_name']) ? $_SESSION['op_name'] : 'undefined';

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
    <link href="css/w2ui-1.4.3.min.css" type="text/css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/w2ui-1.4.3-zh-cn.js" type="text/javascript"></script>
    <script src="js/jxc-1.0.0.js?v=<?= time() ?>" type="text/javascript"></script>
<!--  excel export  -->
    <script src="js/FileSaver.min.js" type="text/javascript"></script>
    <script src="js/xlsx.core.min.js" type="text/javascript"></script>

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
    w2utils.settings.phrases = {
        'Yesterday' :'昨天',
        'Loading...' :'加载...',
        'All Fields' :'全部字段',
        'Are you sure you want to delete selected records?' :'是否确认要删除该记录?',
        'Returned data is not in valid JSON format.' :'返回的数据不是一个有效的JSON格式.',
        'AJAX error. See console for more details.' :'AJAX错误. 在console中查看更多信息.',
        'Refreshing...' :'刷新中...',
        'Reload data in the list' :'重新加载数据',
        'Show/hide columns' :'显示/隐藏 列',
        'Search...' :'搜索...',
        'Add New' :'新增',
        'Edit' :'编辑',
        'Delete' :'删除',
        'Save' :'保存',
        'Open Search Fields' :'打开搜索字段',
        'Add new record' :'新增一条记录',
        'Edit selected record' :'编辑选中的记录',
        'Delete selected records' :'删除选中的记录',
        'Save changed records' :'保存变更的记录',
        'Sorting took' :'排序耗时',
        'sec' :'秒',
        'Search took' :'查询耗时',
        'Load' :'加载',
        'More' :'更多',
        'Server Response' :'服务器响应',
        'Delete Confirmation' :'确认删除框',
        'Multiple Fields' :'多个字段',
        'Line #' :'行',
        'Skip' :'跳过',
        'Records' :'记录',
        'Save Grid State' :'保存表单状态',
        'Restore Default State' :'重置缺省状态',
        'Clear Search' :'清除搜索',
        'is' :'等于',
        'begins' :'开始',
        'contains' :'包含',
        'ends' :'结束',
        'in' :'in', //
        'not in' :'not in', //
        'of' :'of', //
        'between' :'区间',
        'Reset' :'重置',
        'buffered' :'缓存的',
        'selected' :'选中的',
        'Column' :'列',
        'Record ID' :'记录ID',
        'Notification' :'Notification', //
        'Ok' :'Ok', //
        'Confirmation' :'确认',
        'Hide' :'隐藏',
        'Show' :'显示',
        'Attach files by dragging and dropping or Click to Select' :'Attach files by dragging and dropping or Click to Select',
        'Remove' :'移除',
        'Name' :'Name',
        'Size' :'Size',
        'Type' :'Type',
        'Modified' :'Modified',
        'No matches' :'没有匹配',
        'Type to search....' :'Type to search....',
        'Return data is not in JSON format.' :'返回的不是JSON格式数据',
        'Saving...' :'正在保存....',
        'Not an integer' :'不是整数',
        'Not a float' :'不是浮点数',
        'Not in money format' :'不是货币格式',
        'Not a hex number' :'不是16进制数',
        'Not a valid email' :'不是有效邮箱',
        'Not a valid date' :'不是有效日期',
        'Required field' :'必须字段',
        'Field should be equal to ' :'字段应该等同于'
    };

//    w2utils.settings.dateFormat = "yyyy-mm-dd";
//    w2utils.settings.timeFormat = "hh:mi pm";

    //  initialize layout
    $('#layout').height($(window).height());
    $('#layout').w2layout({
        name: 'layout',
        panels: [
            {type: 'top', size: 50, content: '<?=$op_name?>'},
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