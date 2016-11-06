<?php
/**
 * 管理产品基本信息.
 *
 */
include_once "../Templates/include.php";
?>
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
$remoteUrl = "../Jxc/index.php?api=mg_product";
?>
<script>
    $(document).ready(function () {
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

        var content = $('#div_right').w2grid({
            name: 'div_frame',
            header: '产品信息管理',
            multiSelect: true,
            url: "<?=$remoteUrl ?>",
            columns: [
                {field: 'pdt_id', caption: '编号', size: '10%', editable: {type: 'text'}},
                {field: 'pdt_name', caption: '名称', size: '10%', editable: {type: 'text'}},
                {field: 'pdt_color', caption: '颜色', size: '5%', editable: {type: 'text'}},
                {field: 'pdt_price', caption: '进货价', size: '5%', editable: {type: 'text'}}
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
            onSubmit: function (event) {
                var pdt_id = w2GridCheckUniqueID(this, 'pdt_id');
                if (pdt_id) {
                    event.preventDefault();
                    w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                }
            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onAdd: w2GridOnAdd,
            onEditField: w2GridOnEditField,
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);

//        $.ajax

        //  根据货号筛选
        $.getJSON("../Jxc/index.php?api=get_pdt_id_list", null, function (data) {
            if (data['status'] == 'success') {
                var item = {
                    type: 'menu', id: 'selectPdt', caption: '选择货号',
                    items: data['items']
                };
                w2ui['div_frame'].toolbar.add(item);
                w2ui['div_frame'].toolbar.refresh();
            }
        });
    });
</script>
</html>