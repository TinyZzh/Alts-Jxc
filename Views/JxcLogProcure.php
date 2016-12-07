<?php
/**
 * 采购日志.
 */
include_once "../Templates/include.php";
use Jxc\Impl\Core\JxcConst;

$type = JxcConst::IO_TYPE_PROCURE;
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    var jxcType = <?=$type?>;
    var configJxc = {
        header : '采 购 日 志',
        urls : {
            'getOrderAll': 'Jxc/do.php?api=order&c=getOrderAll&type=' + jxcType,
            'getOrderDetail': 'Jxc/do.php?api=order&c=getOrderDetail'
        }
    };
</script>
<?php
include_once "../Templates/TplJxcOrderLog.php";
?>
</html>