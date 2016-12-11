<?php

include_once "../AutoLoader.php";
include_once "../Impl/Core/JxcConfig.php";
Jxc\AutoLoader::register();

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\OpIdentifyDao;
use Jxc\Impl\Vo\VoOpIdentify;



$apiList = [
    array("func"=>"analysisProfit"),
    array("func"=>"w2Records"),
    array("func"=>"getColorInfo"),
    array("func"=>"saveColorInfo"),
    array("func"=>"removeColorInfo"),
    array("func"=>"getCustomList"),
    array("func"=>"records"),
    array("func"=>"saveCustomerInfo"),
    array("func"=>"removeCustomerInfo"),
    array("func"=>"charge"),
    array("func"=>"opCreate"),
    array("func"=>"getSelfOperator"),
    array("func"=>"w2GetSelfOperator"),
    array("func"=>"w2OpChangeAuth"),
    array("func"=>"w2OpChangeSelfInfo"),
    array("func"=>"w2OpChangeSelfPsw"),
    array("func"=>"opChangeStatus"),
    array("func"=>"opChangeAuth"),
    array("func"=>"opChangeSelfPsw"),
    array("func"=>"opChangeSelfInfo"),
    array("func"=>"opChangeOtherInfo"),
    array("func"=>"getOrderAll"),
    array("func"=>"getSalesOrder"),
    array("func"=>"getOrderDetail"),
    array("func"=>"getPdtList"),
    array("func"=>"pdtW2gridRecords"),
    array("func"=>"savePdtInfo"),
    array("func"=>"removePdtInfo"),
    array("func"=>"saveSalesOrder"),
    array("func"=>"procure"),
    array("func"=>"sell"),
    array("func"=>"refund"),
    array("func"=>"login"),
    array("func"=>"logout"),
];

$opIdentifyDao = new OpIdentifyDao(JxcConfig::$DB_Config);
foreach (JxcConfig::$JXC_SERVICE as $k => $clz) {
    $methods = get_class_methods($clz);
    foreach ($methods as $method) {
        if (!JxcService::isProtected($clz, $method)) {
            $query = "INSERT INTO `erp_jxc`.`cfg_api` (`name`, `func`, `lv`, `comment`) VALUES ('{$method}', '{$method}', '1', ''); ";
            $opIdentifyDao->mysqlDB()->ExecuteSQL($query);
        }
    }
}



