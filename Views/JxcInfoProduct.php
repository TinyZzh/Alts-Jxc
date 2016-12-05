<?php
/**
 * 管理产品基本信息.
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
        //
        colorUtils.onDestroy();

        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '产品信息管理',
            multiSelect: true,
            url: {
                'get': 'Jxc/do.php?api=product&c=getPdtList',
                'remove': 'Jxc/do.php?api=product&c=removePdtInfo',
                'save': 'Jxc/do.php?api=product&c=savePdtInfo'
            },
            columns: [
                {field: 'pdt_id', caption: '编号', size: '10%', editable: {type: 'text'}},
                {field: 'pdt_name', caption: '名称', size: '10%', editable: {type: 'text'}},
                {
                    field: 'pdt_color',
                    caption: '颜色',
                    size: '80px',
                    editable: {type: 'text'},
                    render: W2Util.renderJxcColorCell
                },
                {field: 'pdt_price', caption: '单价', size: '5%', editable: {type: 'money:2'}},
                {field: 'datetime', caption: '日志时间', size: '5%'}
            ],
            show: {
                toolbar: true,
                toolbarAdd: true,
                toolbarDelete: true,
                lineNumbers: true,
                header: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'pdt_save', caption: '保存', icon: 'w2ui-icon-check',
                        onClick: function (event) {
                            console.log(event);
                            console.log(this);
                            var grid = w2ui['div_main_cnt'];
                            var pdt_id = w2GridCheckUniqueID(grid, 'pdt_id');
                            if (pdt_id) {
                                w2alert("[Error]货号[" + pdt_id + "]重复, 请重新输入.", "Error");
                                return;
                            }
                            if (grid.getChanges().length <= 0) {
                                w2alert("[Msg]数据没有变更，不需要保存.", "Message");
                                return;
                            }
                            w2confirm("是否确定提交?", "确认提示").yes(function () {
                                grid.save();
                            });
                        }
                    },
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
                var that = this;
                //  转换为color_id
                for (var r in that.records) {
                    var rec = that.records[r];
                    if (typeof rec['changes'] != 'undefined') {
                        console.log(rec['changes'].pdt_color);
                        if (rec['changes'].pdt_color && $.isPlainObject(rec['changes'].pdt_color)) {
                            rec['changes'].pdt_color = rec['changes'].pdt_color.color_id;
                        }
                    }
                }
            },
            onLoad: function (event) {
                var that = this;
//                $.getJSON("Jxc/do.php?api=product&c=getPdtList", [], function (data) {
//                    console.log(data);
//                    if (data['state'] == 1) {
//                        that.add(data['data']);
//                    }
//                    w2uiInitEmptyGrid(that, event);
//                });

                w2uiInitEmptyGrid(that, event);
            },
            onAdd: w2GridOnAdd,
//            onEditField: w2GridOnEditField,
            onEditField: function (event) {
                console.log(event);
                var that = this;
                if (this.columns[event.column].field == 'pdt_color') {  //  编辑单品颜色
                    event.preventDefault();
//                    colorUtils.openPop(this, event);
                    $.getJSON("Jxc/do.php?api=color&c=w2Records", null, function (data) {
                        if (data['status'] == 'success') {
                            var colorsOptions = popupColorsOption(that, event.index, event.column, 'pop_w2grid_colors', data['records']);
                            PopupUtil.onPopupShow({
                                subOptions: colorsOptions
                            });
                        }
                    });
                }
            },
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2GridOnKeyDown
        });
        w2ui['layout'].content('main', content);


    });
</script>
</html>