<?php
/**
 * 退货日志.
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
    $().data("jxc_products", <?=json_encode($w2Products)?>);

    var configJxc = {
        header : '退 货 日 志',
        urls : {
            'getOrderAll': 'Jxc/do.php?api=order&c=getOrderAll&type=' + <?=\Jxc\Impl\Core\JxcConst::IO_TYPE_REFUND?>,
            'getOrderDetail': 'Jxc/do.php?api=order&c=getOrderDetail'
        }
    };

</script>
<?php
include_once "../Templates/TplJxcOrderLog.php";
?>
</html>