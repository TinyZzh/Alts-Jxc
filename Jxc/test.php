<?php

header("Content-type: text/html; charset=utf-8");

include_once __DIR__ . "/AutoLoader.php";
include_once "./Impl/Core/JxcConfig.php";
Jxc\AutoLoader::register();

use Jxc\Impl\Libs\W2UI;
use Jxc\Impl\Service\ProductService;
use Jxc\Impl\Vo\VoProduct;


$service = new ProductService();
var_dump(ProductService::$PUBLIC_FUNC);
$ref = new ReflectionMethod(get_class($service), "getPdtList");
if ($ref->isPublic()) {
    echo true;
}


$vo = new VoProduct();
$vo->pdt_id = 8818;
$vo->pdt_name = '8818-name';
$vo->datetime = \Jxc\Impl\Libs\DateUtil::makeTime();
$vo->pdt_color = 3;
$vo->pdt_counts = array(0,1,2,3,4,5,6,7,8);
$vo->pdt_price = 100;


echo json_encode($vo).'<br/>';
$voW2 = W2UI::objToW2ui($vo);
echo json_encode($voW2).'<br/>';


$vo2 = new VoProduct();
$obj = W2UI::w2uiToObj($vo2, $voW2);
echo json_encode($obj).'<br/>';



//$dao = new \Jxc\Impl\Dao\LogSalesDao($DB_Config);
//$vo = new \Jxc\Impl\Vo\LogSales();
//$vo = $dao->insert($vo);
//var_dump($vo);






