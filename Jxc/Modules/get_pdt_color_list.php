<?php

use Jxc\Impl\Dao\LogSalesDao;

$response = array('status' => "success",);

$loaSalesDao = new LogSalesDao($DB_Config);

$request = $_REQUEST;


$datas = $loaSalesDao->selectAll();
$array = array();
foreach ($datas as $k => $data) {
    $array[] = array('id' => $k, 'text' => "货号" . $data->pdt_id);
}

$response['items'] = $array;

echo json_encode($response);