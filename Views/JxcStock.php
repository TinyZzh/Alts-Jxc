<?php
/**
 * 采购管理.
 *
 */
include_once "../Templates/include.php";

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\CustomerDao;

$dao = new CustomerDao(JxcConfig::$DB_Config);
$resultSet = $dao->selectCustomNameList();
$custom_list = array();
foreach ($resultSet as $k => $v) {
    $custom_list[] = array('id' => $k, 'text' => $v['ct_name']);
}
$pub_custom_list = json_encode($custom_list);
//
$remoteUrl = "../Jxc/index.php?api=mg_product";
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '采购',
            multiSelect: true,
            url: "<?=$remoteUrl ?>",
            columnGroups: [
                {caption: '产品', span: 2},
                {caption: '颜色', master: true},
                {caption: '尺码', span: 9},
                {caption: '进货价', master: true}
            ],
            columns: [
                {field: 'pdt_id', caption: '编号', size: '10%'},
                {field: 'pdt_name', caption: '名称', size: '10%'},
                {field: 'pdt_color', caption: '颜色', size: '5%'},
                {field: 'pdt_count_0', caption: '3XS', size: '5%'},
                {field: 'pdt_count_1', caption: '2XS', size: '5%'},
                {field: 'pdt_count_2', caption: 'XS', size: '5%'},
                {field: 'pdt_count_3', caption: 'S', size: '5%'},
                {field: 'pdt_count_4', caption: 'M', size: '5%'},
                {field: 'pdt_count_5', caption: 'L', size: '5%'},
                {field: 'pdt_count_6', caption: 'XL', size: '5%'},
                {field: 'pdt_count_7', caption: '2XL', size: '5%'},
                {field: 'pdt_count_8', caption: '3XL', size: '5%'},
                {field: 'pdt_price', caption: '进货价', size: '5%'}
            ],
            show: {
                toolbar: true,
                toolbarAdd: true,
                toolbarSave: true,
                toolbarDelete: true,
                lineNumbers: true,
                header: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {type: 'button', id: 'mybutton', caption: 'My other button', img: 'icon-folder'},
                    {type: 'button', id: 'newLogSales', caption: '新增销售记录',},
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