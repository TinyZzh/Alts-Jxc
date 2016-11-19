<?php
/**
 * 废弃产品管理.
 */
include_once "../Templates/include.php";
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    $(document).ready(function () {
        //  修改删除按钮的文字
        var btns = w2obj.grid.prototype.buttons;
        btns['delete'].caption = w2utils.lang('恢复');
        btns['delete'].hint = w2utils.lang('恢复选中的记录');
        //  渲染w2grid
        var content = $('#div_main_cnt').w2grid({
            name: 'div_main_cnt',
            header: '废弃产品管理',
            multiSelect: true,
            url: {
                'get': 'Jxc/do.php?api=product&c=getPdtList&flag=1',
                'remove': 'Jxc/do.php?api=product&c=removePdtInfo&flag=1'
            },
            columns: [
                {field: 'pdt_id', caption: '货号', size: '5%'},
                {field: 'pdt_name', caption: '名称', size: '10%'},
                {
                    field: 'pdt_color', caption: '颜色', size: '80px',
                    render: function (record, index, col_index) {
                        var value = this.getCellValue(index, col_index);
                        if (cacheOfColors[value]) {
                            var vc = cacheOfColors[value];
                            return '<div style="height:24px;text-align:center;background-color: #' + vc.color_rgba + ';">' + ' ' + vc.color_name + '</div>';
                        }
                        return '<div>' + value + '</div>';
                    }
                },
                <?php
                // {field: 'pdt_count_1', caption: '2XS', size: '5%', editable: {type: 'text'}, render: renderSizeField},
                    $array = array( '3XS', '2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL' );
                    foreach ($array as $k => $v) {
                        echo "{field: 'pdt_count_{$k}', caption: '{$v}', size: '5%'},";
                    }
                ?>
                {field: 'pdt_price', caption: '进货价', size: '5%'},
                {field: 'datetime', caption: '记录时间', size: '150px'},
                {field: 'pdt_total', caption: '总数量', size: '5%'},
                {field: 'total_rmb', caption: '总价', size: '5%'},
                {field: 'timeLastOp', caption: '最后一次操作时间', size: '150px'},
            ],
            show: {
                toolbar: true,
                header: true,
                footer: true,
                lineNumbers:true,
                toolbarDelete:true
            },
            toolbar: {
                items: [
                    {type: 'break'}
                ]
            }
        });
        w2ui['layout'].content('main', content);
    });
</script>
</html>