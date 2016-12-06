<?php
use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\ColorDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Vo\VoProduct;

include_once "../Templates/include.php";
//
$productDao = new ProductDao(JxcConfig::$DB_Config);
$w2Products = $productDao->w2uiSelectAll();
$pdt_list = array();
foreach ($w2Products as $v) {
    $w2ValRecId = $v['pdt_id'];;
    $pdt_list[] = $w2ValRecId;
}

//  颜色缓存
$colorDao = new ColorDao(JxcConfig::$DB_Config);
$cacheOfColors = $colorDao->selectAll();
$menuOfColors = $colorDao->w2uiSelectAll();

//  产品信息缓存
$productDao = new ProductDao(JxcConfig::$DB_Config);
$products = $productDao->selectAll();
$cacheOfPdtInfo = array();
$pdt_list = array();
foreach ($products as $k => $v) {
    if ($v instanceof VoProduct) {
        $cacheOfPdtInfo[$v->pdt_id] = $v;
        $w2ValRecId = array('id' => $k, 'text' => $v->pdt_id);
        $pdt_list[] = $w2ValRecId;
        $cacheOfPdtInfo[$v->pdt_id]->pdt_id = $w2ValRecId;
    }
}


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
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/w2ui-1.4.3-zh-cn.js" type="text/javascript"></script>
    <script src="../js/jxc-1.0.0.js?v=<?= time() ?>" type="text/javascript"></script>
</head>
<body id="body">
<div id="layout">
    <div id="jxc_nav" class="navbar navbar-default navbar-fixed-top" role="navigation"><p>导航栏</p></div>
    <div id="div_left"></div>
    <div id="div_right"></div>
    <div id="div_footer"></div>
</div>
<script>
    var cacheOfColors = <?=json_encode($cacheOfColors)?>;
    var menuOfColors = <?=json_encode($menuOfColors)?>;
    console.log(menuOfColors);
    var cacheOfPdtInfo =<?=json_encode($cacheOfPdtInfo)?>;

    $(document).ready(function () {
        //  layout
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

        var formOptions = {
            header: 'Edit Record',
            name: 'form',
            fields: [
                {name: 'recid', type: 'text', html: {caption: 'ID', attr: 'size="10" readonly'}},
                { name: 'fname', type: 'text', required: true, html: {caption: 'First Name', attr: 'size="40" maxlength="40"'}},
                { name: 'lname', type: 'text', required: true, html: {caption: 'Last Name', attr: 'size="40" maxlength="40"'}},
                {name: 'email', type: 'email', html: {caption: 'Email', attr: 'size="30"'}},
                {name: 'sdate', type: 'date', html: {caption: 'Date', attr: 'size="10"'}}
            ],
            actions: {
                Reset: function () {
                    this.clear();
                },
                Save: function () {
                    var errors = this.validate();
                    if (errors.length > 0) return;
                    if (this.recid == 0) {
                        w2ui.grid.add($.extend(true, {recid: w2ui.grid.records.length + 1}, this.record));
                        w2ui.grid.selectNone();
                        this.clear();
                    } else {
                        w2ui.grid.set(this.recid, this.record);
                        w2ui.grid.selectNone();
                        this.clear();
                    }
                }
            }
        };
        //  content
        var content = $().w2form(formOptions);
        w2ui['layout'].content('main', content);
    });
</script>
</html>