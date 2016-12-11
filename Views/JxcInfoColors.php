<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    var configJxc = {
        header: '颜色信息管理',
        urls: {
            'get': 'Jxc/do.php?api=color&c=w2GetColorInfo',
            'save': 'Jxc/do.php?api=color&c=saveColorInfo',
            'remove': 'Jxc/do.php?api=color&c=removeColorInfo'
        },
        columns: [
            {field: 'color_id', caption: '颜色ID', size: '10%', style: 'text-align:center', summary: true},
            {
                field: 'color_rgba', caption: 'RGBA', size: '80px', editable: {type: 'color'},
                render: function (record, index, col_index) {
                    var rgba = this.getCellValue(index, col_index);
                    return '<div style="height:24px;text-align:center;background-color: \#' + rgba + ';">' + ' ' + rgba + '</div>';
                }
            },
            {field: 'color_name', caption: '名称', size: '10%', style: 'text-align:center', editable: {type: 'text'}}
        ]
    };
</script>
<?php
include_once "../Templates/TplJxcInfo.php";
?>
</html>