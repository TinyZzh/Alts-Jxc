<?php

use Jxc\Impl\Dao\LogSalesDao;
use Jxc\Impl\Vo\LogSales;

$response = array('status' => "error",);

$logSalesDao = new LogSalesDao($DB_Config);

switch ($_REQUEST['cmd']) {
    case "get-records": {
        $datas = $logSalesDao->selectAll();
        $array = array();
        foreach ($datas as $data) {
            if ($data instanceof LogSales)
                $array[] = $data->voToW2ui($data);
        }
        $response['status'] = 'success';
        $response['total'] = count($array);
        $response['records'] = $array;
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
        $existMap = $logSalesDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voLogSales = $existMap[$id];
                if ($voLogSales instanceof LogSales) {
                    $voLogSales->w2uiToVo($change);
                    $logSalesDao->updateByFields($voLogSales);
                    $updateAry[] = $voLogSales->voToW2ui($voLogSales);
                }
            } else {    //  insert
                $voLogSales = new LogSales();
                $voLogSales->w2uiToVo($change);
                $voLogSales = $logSalesDao->insert($voLogSales);
                $ua = $voLogSales->voToW2ui($voLogSales);
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
        foreach ($_REQUEST['selected'] as $recid) {
            $logSalesDao->delete($recid);
        }
        $response['status'] = 'success';
        break;
    }
    default:
        $response['message'] = 'Undefined cmd.';
        break;
}

echo json_encode($response);