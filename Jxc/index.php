<?php
header("Content-type: text/html; charset=utf-8");

include_once __DIR__ . "/AutoLoader.php";
include_once __DIR__ . "/Config.inc.php";
Jxc\AutoLoader::register();

use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Vo\VoProduct;

echo "Tinyz";


$product = new VoProduct();

$product->pdt_id = "8025";
$product->pdt_color = 15;
$product->pdt_stock = array(1, 2, 3, 4, 5, 6, 7, 8, 0);
$product->pdt_name = "名称";
$product->pdt_purchase = 115;


$pdtDao = new ProductDao($DB_Config);
$product = $pdtDao->insert($product);

var_dump($product);


var_dump($pdtDao->selectAll());


