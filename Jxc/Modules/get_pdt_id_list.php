<?php

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\ProductDao;

$productDao = new ProductDao(JxcConfig::$DB_Config);

$resultSet = $productDao->selectPdtIdList();
$list = array();
foreach ($resultSet as $k => $v) {
    $list[] = array('id' => $k, 'text' => $v['pdt_id']);
}
$pub_pdt_id_list = json_encode($list);
unset($list);
unset($productDao);
echo $pub_pdt_id_list;