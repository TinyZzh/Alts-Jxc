<?php
/**
 * 退货日志.
 */
include_once "../Templates/include.php";
use Jxc\Impl\Core\JxcConst;

$type = JxcConst::IO_TYPE_REFUND;
?>
<!DOCTYPE html>
<html lang="zh-cn">
<body id="body">
</body>
<script>
    var jxcType = <?=$type?>;
    var configJxc = {
        header : '退 货 日 志',
        urls : {
            'getOrderAll': 'Jxc/do.php?api=order&c=getOrderAll&type=' + jxcType,
            'getOrderDetail': 'Jxc/do.php?api=order&c=getOrderDetail'
        }
    };
</script>
<?php
include_once "../Templates/TplJxcOrderVerify.php";
?>
</html>