<?php include_once "../Templates/include.php"; ?>
<!DOCTYPE html>
<html lang="zh-cn">
<?php include_once "../Templates/head.html"; ?>
<body id="body">
<?php include_once "../Templates/layout.html"; ?>
</body>
<?php
use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\CustomerDao;

$customDao = new CustomerDao(JxcConfig::$DB_Config);
$resultSet = $customDao->selectCustomNameList();
$custom_list = array();
foreach ($resultSet as $k => $v) {
    $custom_list[] = array('id' => $k, 'text' => $v['ct_name']);
}
$pub_custom_list = json_encode($custom_list);
//
$remoteUrl = "../Jxc/index.php?api=jxc_product";


$listOfCustom = json_encode($customDao->w2uiSelect());

?>
<script>
    $(document).ready(function () {
        console.log(<?=$listOfCustom?>);
//        $.getJSON('../do.php?api=custom&c=getCustomList', null, function(data) {
//            if (data.state != 1) {
//                w2alert("Error: Api:[custom], Cmd:[getCustomList]", "Error");
//            }
//
//
//        });


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
                    {type: 'button', id: 'JxcCustomName', text: '客户名字', img: 'icon-folder', disabled:true},
                    {type: 'button', id: 'JxcCustomAddress', text: '客户练习地址', img: 'icon-folder', disabled:true},
                    {type: 'button', id: 'newLogSales', caption: '新增销售记录',},
                    {
                        type: 'menu', id: 'JxcCustomMenu', caption: '客户',
                        items: <?=$listOfCustom ?>
                    }
                ],
                onClick: function (target, data) {
                    console.log(target);
                    console.log(data);
                    console.log(this);
                    var that = this;
                    var selected = target.split(':');
                    if (selected.length > 1 && selected[0] == 'JxcCustomMenu') {
                        that.set('JxcCustomMenu', {'caption':data.subItem.text});
                        that.set('JxcCustomName', {'text':data.subItem.text});
                        that.set('JxcCustomAddress', {'text':data.subItem.ct_address});

                        that.uncheck(selected[0]);
                    }


                }
            },
            onLoad: function (event) {
                w2uiInitEmptyGrid(this, event);
            },
            onEditField: function (event) {
                var that = this;
                var nextRow = that.nextRow(that.last.sel_ind);
                if (nextRow == null) w2GridAddEmptyRecord(that);
            },
            onAdd: function (event) {
                w2GridAddEmptyRecord(this);
            },
            onSave: w2GridOnSaveAndUpdate,
            onKeydown: w2uiFuncGridOnKeydown,
            onExpand: function (event) {
                if (w2ui.hasOwnProperty('subgrid-' + event.recid)) w2ui['subgrid-' + event.recid].destroy();
                $('#' + event.box_id).css({
                    margin: '0px',
                    padding: '0px',
                    width: '100%'
                }).animate({height: '105px'}, 100);
                setTimeout(function () {
                    $('#' + event.box_id).w2grid({
                        name: 'subgrid-' + event.recid,
                        show: {columnHeaders: true},
                        fixedBody: true,
                        columns: [
                            {field: 'lname', caption: 'Last Name', size: '30%'},
                            {field: 'fname', caption: 'First Name', size: '30%'},
                            {field: 'email', caption: 'Email', size: '40%'},
                            {field: 'sdate', caption: 'Start Date', size: '90px'},
                        ],
                        records: [
                            {recid: 6, fname: 'Francis', lname: 'Gatos', email: 'jdoe@gmail.com', sdate: '4/3/2012'},
                            {recid: 7, fname: 'Mark', lname: 'Welldo', email: 'jdoe@gmail.com', sdate: '4/3/2012'},
                            {recid: 8, fname: 'Thomas', lname: 'Bahh', email: 'jdoe@gmail.com', sdate: '4/3/2012'}
                        ]
                    });
                    w2ui['subgrid-' + event.recid].resize();
                }, 300);
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
    });
</script>
</html>

