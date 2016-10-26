<?php
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2016/10/26
 * Time: 0:33
 */

include_once __DIR__ . "/AutoLoader.php";
Jxc\AutoLoader::register();

use Jxc\Impl\Vo\VoProduct;

echo "Tinyz";


$product = new VoProduct();

$product->id = 1;
$product->pdt_color = "";
$product->pdt_stock = array(1,2,3,4,5,6,7,8,0);
$product->pdt_name = "Ãû³Æ";

$map = $product->toArray();
var_dump($map);

$pdt2 = new VoProduct();
$pdt2->convert($map);
var_dump($pdt2);

