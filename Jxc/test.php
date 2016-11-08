<?php

header("Content-type: text/html; charset=utf-8");

include_once __DIR__ . "/AutoLoader.php";
include_once "./Impl/Core/JxcConfig.php";
Jxc\AutoLoader::register();

use Jxc\Impl\Service\ProductService;


$service = new ProductService();

var_dump(ProductService::$PUBLIC_FUNC);


$ref = new ReflectionMethod(get_class($service), "vv");
if ($ref->isPublic()) {
    echo true;
}





//$dao = new \Jxc\Impl\Dao\LogSalesDao($DB_Config);
//$vo = new \Jxc\Impl\Vo\LogSales();
//$vo = $dao->insert($vo);
//var_dump($vo);






