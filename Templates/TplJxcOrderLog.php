<?php
/**
 * 日志模板
 */
use Jxc\Impl\Core\JxcConst;

?>
<script>
    $(document).ready(function () {
        var logCnt = $().w2grid({
            name: 'div_main_cnt',
            header: configJxc.header,
            multiSelect: true,
            columns: [
                {field: 'order_id', caption: '订单号', size: '10%', style: 'text-align:center'},
                <?php
                if (JxcConst::IO_TYPE_PROCURE != $type)
                    echo "{field: 'ct_name', caption: '客户', size: '10%', style: 'text-align:center'},";
                //{field: 'ct_name', caption: '客户', size: '10%', style: 'text-align:center'},
                ?>
                {field: 'log_date', caption: '日期', size: '10%', style: 'text-align:center', render: 'date:yyyy-dd-mm'},
                {field: 'datetime', caption: '日志时间', size: '10%', style: 'text-align:center'},
                {field: 'total_rmb', caption: '总计金额', size: '10%', render: 'money:2'},
                {field: 'op_name', caption: '操作员', size: '10%', style: 'text-align:center'}
            ],
            searches: [
                {field: 'order_id', caption: '订单号', type: 'int'},
                {field: 'ct_name', caption: '客户姓名', type: 'text'},
                {field: 'log_date', caption: '日期', type: 'date'}
            ],
            show: {toolbar: true, header: true, footer: true, lineNumbers: true, toolbarSearch: true},
            toolbar: {
                items: [
                    {type: 'break'},
                    {
                        type: 'button', id: 'export_excel', caption: '导出Excel',
                        onClick: function (event) {
                            console.log(event);
                            W2Util.exportW2grid(w2ui['div_main_cnt'].name, w2ui['div_main_cnt']);
                        }
                    }
                ]
            },
            onExpand: function (event) {
                var expandEvent = event;
                console.log(event);
                $.ajax({
                    type: 'GET',
                    url: configJxc.urls['getOrderDetail'],
                    data: {
                        'order_id': expandEvent.recid
                    },
                    dataType: 'JSON'
                }).done(function (data, status, xhr) {
                    console.log(data);
                    if (data.status != 'success') {
                        w2alert(data.message, "Error");
                    } else {
                        var w2Columns = [
                            {field: 'pdt_id', caption: '编号', size: '10%', style: 'text-align:center'},
                            {field: 'pdt_name', caption: '名称', size: '10%', style: 'text-align:center'},
                            {field: 'pdt_color', caption: '颜色', size: '80px', render: W2Util.renderJxcColorCell},
                            <?php
                            // {field: 'pdt_count_0', caption: '3XS', size: '50px', style:'text-align:center'},
                            $array = array('3XS', '2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL');
                            foreach ($array as $k => $v) {
                                echo "{field:'pdt_count_{$k}',caption: '{$v}',size:'55px',style:'text-align:center'},";
                            }
                            ?>
                            {field: 'pdt_zk', caption: '折扣', size: '80px', render: 'percent'},
                            {field: 'pdt_price', caption: '单价', size: '80px', render: 'money:2'},
                            {field: 'pdt_total', caption: '总数量', size: '80px'},
                            {field: 'total_rmb', caption: '总价', size: '120px', render: 'money:2'},
                            {field: 'pdt_comment', caption: '备注', size: '15%'}
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
            url: configJxc.urls['getOrderAll'],
            data: {},
            dataType: 'JSON'
        }).done(function (data, status, xhr) {
            console.log(data);
            if (data.status != 'success') {
                w2alert(data.message, "Error");
            } else {
                w2ui['div_main_cnt'].add(data.records);
                $('#div_main_cnt').w2render('div_main_cnt');
                w2ui['layout'].content('main', w2ui['div_main_cnt']);
            }
        }).fail(function (xhr, status, error) {
            w2alert('HTTP ERROR:[' + error.message + ']', "Error");
        });

        function showExpand(eventOfExpand, w2Columns, w2Records) {
            var height = (Math.min(10, w2Records.length) + 1) * 24 + 10;
            if (w2ui.hasOwnProperty('subgrid-' + eventOfExpand.recid)) w2ui['subgrid-' + eventOfExpand.recid].destroy();
            $('#' + eventOfExpand.box_id).css({
                margin: '0px',
                padding: '0px',
                width: '100%'
            }).animate({height: height + 'px'}, 100);//            }).animate({height: '105px'}, 100);
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