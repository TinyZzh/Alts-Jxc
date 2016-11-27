<?php
/**
 * 查看采购订单日志.
 */
use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\ProductDao;

include_once "../Templates/include.php";
//
$productDao = new ProductDao(JxcConfig::$DB_Config);
$w2Products = $productDao->w2uiSelectAll();
$pdt_list = array();
foreach ($w2Products as $v) {
    $w2ValRecId = $v['pdt_id'];;
    $pdt_list[] = $w2ValRecId;
}


?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        $().data("jxc_products", <?=json_encode($w2Products)?>);
//        console.log($(document).data("xx"));
//        console.log(this);
//        console.log($(document));

        var logCnt = $().w2grid({
            name: 'div_main_cnt',
            header: '采购日志',
            multiSelect: true,
            columns: [
                {field: 'order_id', caption: '订单号', size: '10%', style: 'text-align:center'},
                {field: 'ct_name', caption: '客户', size: '10%', style: 'text-align:center'},
                {field: 'datetime', caption: '日志时间', size: '10%', style: 'text-align:center'},
                {field: 'total_rmb', caption: '总计金额', size: '10%', style: 'text-align:center'},
                {field: 'op_name', caption: '操作员', size: '10%', style: 'text-align:center'},
            ],
            show: {toolbar: true, header: true, footer: true, lineNumbers: true},
            onExpand: function (event) {
                var expandEvent = event;
                console.log(event);
                var ajaxOptions = {
                    type: 'GET',
                    url: 'Jxc/do.php?api=order&c=getOrderDetail',
                    data: {
                        'order_id': expandEvent.recid
                    },
                    dataType: 'JSON'
                };
                $.ajax(ajaxOptions)
                    .done(function (data, status, xhr) {
                        if (data.status != 'success') {
                            w2alert(data.message, "Error");
                        } else {
                            console.log(data);
                            var w2Columns = [
                                {field: 'pdt_id', caption: '编号', size: '10%', style: 'text-align:center'},
                                {field: 'pdt_name', caption: '名称', size: '10%', style: 'text-align:center'},
                                {
                                    field: 'pdt_color', caption: '颜色', size: '80px',
                                    render: function (record, index, col_index) {
                                        var html = this.getCellValue(index, col_index);
                                        if (cacheOfColors[html]) {
                                            var vc = cacheOfColors[html];
                                            return '<div style="height:24px;text-align:center;background-color: #' + vc.color_rgba + ';">' + ' ' + vc.color_name + '</div>';
                                        }
                                        return '<div>' + html + '</div>';
                                    }
                                },
                                <?php
                                // {field: 'pdt_count_0', caption: '3XS', size: '5%', editable: {type: 'text'}},
                                $array = array('3XS', '2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL');
                                foreach ($array as $k => $v) {
                                    echo "{field: 'pdt_count_{$k}', caption: '{$v}', size: '5%'},";
                                }
                                ?>
                                {field: 'pdt_zk', caption: '折扣', size: '7%', render: 'percent'},
                                {field: 'pdt_price', caption: '进价', size: '7%', render: 'float:2'},
                                {field: 'pdt_total', caption: '总数量', size: '10%'},
                                {field: 'total_rmb', caption: '总价', size: '10%'}
                            ];
                            showExpand(expandEvent, w2Columns, data.records);
                        }
                    }).fail(function (xhr, status, error) {
                    w2alert('HTTP ERROR:[' + error.message + ']', "Error");
                });
            }
        });

        $.ajax({
            type: 'GET',
            url: 'Jxc/do.php?api=order&c=getOrderAll',
            data: {},
            dataType: 'JSON'
        }).done(function (data, status, xhr) {
            if (data.status != 'success') {
                w2alert(data.message, "Error");
            } else {
                console.log(data.records);
                w2ui['div_main_cnt'].add(data.records);
                $('#div_main_cnt').w2render('div_main_cnt');
                w2ui['layout'].content('main', w2ui['div_main_cnt']);
            }
        }).fail(function (xhr, status, error) {
            w2alert('HTTP ERROR:[' + error.message + ']', "Error");
        });


        function showExpand(eventOfExpand, w2Columns, w2Records) {
            if (w2ui.hasOwnProperty('subgrid-' + eventOfExpand.recid)) w2ui['subgrid-' + eventOfExpand.recid].destroy();
            $('#' + eventOfExpand.box_id).css({
                margin: '0px',
                padding: '0px',
                width: '100%'
            }).animate({height: '105px'}, 100);
            setTimeout(function () {
                $('#' + eventOfExpand.box_id).w2grid({
                    name: 'subgrid-' + eventOfExpand.recid,
                    show: {columnHeaders: true},
                    fixedBody: true,
                    columns: w2Columns,
                    records: w2Records
                });
                w2ui['subgrid-' + eventOfExpand.recid].resize();
            }, 300);
        }

    });
</script>
</html>