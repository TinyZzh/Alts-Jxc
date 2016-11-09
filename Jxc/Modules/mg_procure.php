<?php

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Vo\VoProduct;

$response = array('status' => "error",);

$request = $_REQUEST;

$productDao = new ProductDao(JxcConfig::$DB_Config);

switch ($request['cmd']) {
    case "get-records": {
        $datas = $productDao->selectAll();
        $array = array();
        foreach ($datas as $data) {
            if ($data instanceof VoProduct)
                $array[] = $data->voToW2ui($data);
        }
        $response['status'] = 'success';
        $response['total'] = count($array);
        $response['records'] = $array;
        break;
    }
    case "save-records": {
        if (!isset($request['changes'])) {
            $response['status'] = 'success';
            break;
        }
        $changes = $request['changes'];
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $ids = array_keys($aryId);
        $existMap = $productDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voProduct = $existMap[$id];
                if ($voProduct instanceof VoProduct) {
                    $voProduct->w2uiToVo($change);
                    $productDao->updateByFields($voProduct);
                    $updateAry[] = $voProduct->voToW2ui($voProduct);
                }
            } else {    //  insert
                $voProduct = new VoProduct();
                $voProduct->w2uiToVo($change);
                $voProduct = $productDao->insert($voProduct);
                $ua = $voProduct->voToW2ui($voProduct);
                $ua->depId = $id;
                $updateAry[] = $ua;
            }
        }
        $response['status'] = 'success';
        $response['updates'] = $updateAry;
        break;
    }
    case "delete-records": {
        if (!isset($request['selected']) || count($request['selected']) <= 0) {
            $response['status'] = 'error';
            $response['message'] = 'UnSelected any records.';
            break;
        }
        foreach ($request['selected'] as $recid) {
            $productDao->delete($recid);
        }
        $response['status'] = 'success';
        break;
    }
    default:
        $response['message'] = 'Undefined cmd.';
        break;
}

echo json_encode($response);