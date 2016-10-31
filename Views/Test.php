<?php include_once "../Templates/include.php"; ?>
<!DOCTYPE html>
<html lang="zh-cn">
<?php include_once "../Templates/head.html"; ?>
<body id="body">
<?php include_once "../Templates/layout.html"; ?>
</body>
<?php
use Jxc\Impl\Dao\CustomerDao;

$dao = new CustomerDao($DB_Config);
$resultSet = $dao->selectCustomNameList();
$custom_list = array();
foreach ($resultSet as $k => $v) {
    $custom_list[] = array('id' => $k, 'text' => $v['ct_name']);
}
$pub_custom_list = json_encode($custom_list);
//
$remoteUrl = "../Jxc/index.php?api=jxc_product";
?>
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
                lineNumbers: true,
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
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onEditField: function (event) {
                var that = this;
                var nextRow = that.nextRow(that.last.sel_ind);
                if (nextRow == null) w2GridAddRecord(that);
            },
            onAdd: function (event) {
                w2GridAddRecord(this);
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
                                that.grid.total = that.records.length;
                                that.remove(v['depId']);
                                that.add(v);
                            } else {
                                that.set(v['recid'], v);
                            }
                        });
                    }
                }
            },
            onKeydown: w2uiFuncGridOnKeydown
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
    });
</script>
</html>

