<?php
header("Content-type: text/html; charset=utf-8");

include_once __DIR__ . "/AutoLoader.php";
include_once __DIR__ . "/Config.inc.php";
Jxc\AutoLoader::register();



$dao = new \Jxc\Impl\Dao\LogSalesDao($DB_Config);
$vo = new \Jxc\Impl\Vo\LogSales();
$vo = $dao->insert($vo);
var_dump($vo);



