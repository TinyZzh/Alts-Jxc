<?php
//  Autoload register
include_once "../Jxc/AutoLoader.php";
include_once "../Jxc/Config.inc.php";
Jxc\AutoLoader::register();

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
<div id="layout">
    <div id="jxc_nav" class="navbar navbar-default navbar-fixed-top" role="navigation"><p>导航栏</p></div>
    <div id="div_left"></div>
    <div id="div_right"></div>
    <div id="div_footer"></div>
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
            header: '采购',
            multiSelect: true,
            url: "<?=$remoteUrl ?>",
            columns: [
                {field: 'pdt_id', caption: '货号', size: '10%', editable: {type: 'pdt_id'}},
                {
                    field: 'pdt_name', caption: '名称', size: '10%', resizable: true,
                    editable: {
                        type: 'list',
                        showAll: true,
                        items: <?=$pub_custom_list?>
                    },
                    render: function (record, index, col_index) {
                        var html = this.getCellValue(index, col_index);
//                        console.log(html);
                        return html.text || '';
                    }
                },
                {field: 'pdt_color', caption: '颜色', size: '5%', editable: {type: 'pdt_color'}},
                {field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_2', caption: 'XS', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_3', caption: 'S', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_4', caption: 'M', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_5', caption: 'L', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_6', caption: 'XL', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_7', caption: '2XL', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_count_8', caption: '3XL', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_price', caption: '单价', size: '5%', editable: {type: 'text',}},
                {field: 'pdt_total', caption: '总数', size: '5%'},
                {field: 'total_rmb', caption: '总价', size: '10%'}
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
            onAdd: function (event) {
                var that = this;
                console.log(this);
                w2GridAddRecord(that);
            },
            onSave: function (event) {
                var that = this;
                console.log(event.xhr);
                //  回调更新数据
                if (event.xhr != null) {
                    var cb = JSON.parse(event.xhr.responseText);
                    if (cb['updates']) {
                        cb['updates'].map(function (v) {
                            if (v['depId']) {
//                                that.select(v['depId']);
//                                that.delete(true);
                                that.remove(v['depId']);
                                that.add(v);
                            } else {
                                that.set(v['recid'], v);
                            }
                        });
                    }
                }
            },
            onSubmit: function (event) {
//                console.log(this);
//                var changes = event.changes;
//                for (var i in changes) {
//                    if (this.columns.length != changes[i].length) {
//                        w2alert("请补充完数据:" + changes[i]['recid']);
//                        event.preventDefault();
//                        break;
//                    }
//                }
//                console.log(event);
            },
            onKeydown: function (event) {
                var that = this;
                console.log(event.originalEvent);
                if (event.originalEvent.keyCode == 13
                    && event.originalEvent.ctrlKey
                ) {    //  Ctrl + 回车
                    if (that.records) {
                        var nextRcd = that.nextRow(that.last.sel_recid);
                        console.log(nextRcd);
                        if (nextRcd == null) {
                            var targetRcd = w2GridAddRecord(that);
                            that.selectNone();
                            that.select(targetRcd['recid']);
                            that.editField(targetRcd['recid'], 1);
                        }
                    }
                }
//                if (event.originalEvent.keyCode == 9) {
//                    var nextInd = that.last.sel_col + 1;
//                    if (nextInd < that.columns.length) {
//                        that.editField(that.last.sel_recid, nextInd);
//                    }
//                }
            },
            onEditField: function (event) {
                console.log(event);


            }
        });
        w2ui['layout'].content('main', content);

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

//        $.getJSON("<?//=$customerListUrl?>//", null, function (data) {
//            if (data['status'] == 'success') {
//                console.log(data['items']);
//                w2ui['div_frame'].columns[1].items = data['items'];
//            }
//        });

    })
    ;
    $(function () {

    });
</script>
</html>