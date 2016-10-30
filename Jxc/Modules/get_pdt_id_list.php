<?php

use Jxc\Impl\Dao\ProductDao;

$productDao = new ProductDao($DB_Config);

$resultSet = $productDao->selectPdtIdList();
$list = array();
foreach ($resultSet as $k => $v) {
    $list[] = array('id' => $k, 'text' => $v['pdt_id']);
}
$pub_pdt_id_list = json_encode($list);
unset($list);
unset($productDao);
