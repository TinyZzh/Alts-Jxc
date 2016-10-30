<?php

use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Vo\VoProduct;

$response = array('status' => "error",);

$productDao = new ProductDao($DB_Config);

switch ($_REQUEST['cmd']) {
    case "get-records": {
        $response['status'] = 'success';
        $response['total'] = 0;
        $response['records'] = array();
        break;
    }
    case "save-records": {
        if (!isset($_REQUEST['changes'])) {
            $response['status'] = 'success';
            break;
        }
        $changes = $_REQUEST['changes'];
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
//        $response['status'] = 'error';
        $response['updates'] = $updateAry;
        break;
    }
    case "delete-records": {
        if (!isset($_REQUEST['selected']) || count($_REQUEST['selected']) <= 0) {
            $response['status'] = 'error';
            $response['message'] = 'UnSelected any records.';
            break;
        }
        foreach ($_REQUEST['selected'] as $pdt_id) {
            $productDao->delete($pdt_id);
        }
        $response['status'] = 'success';
        break;
    }
    default:
        $response['message'] = 'Undefined cmd.';
        break;
}

echo json_encode($response);