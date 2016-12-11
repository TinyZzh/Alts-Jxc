<?php
header("Content-type: text/html; charset=utf-8");
//  session
if (!session_id()) {
    session_start();
}
//  Object
include_once __DIR__ . "/AutoLoader.php";
include_once "Impl/Core/JxcConfig.php";
Jxc\AutoLoader::register();
//  HTTP util
//include_once __DIR__ . "/Impl/Libs/Requests.php";
//Requests::register_autoloader();

//
use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\LogInvokeApiDao;
use Jxc\Impl\Vo\LogInvokeApi;

if (!isset($_REQUEST['api']) || !isset(JxcConfig::$JXC_SERVICE[$_REQUEST['api']])) {
    echo json_encode(array('status' => 'error', 'message' => 'Unknown service.'));
    exit();
}
$api = $_REQUEST['api'];
if (!isset($_REQUEST['c'])) {
    echo json_encode(array('status' => 'error', 'message' => 'Unknown command.'));
    exit();
}
$method = $_REQUEST['c'];
$request = $_REQUEST;
$op_id = -1;    //  TODO: 缺省的权限为-1
try {
    if(isset($_SESSION['op_id'])) {
        $op_id = $_SESSION['op_id'];
    }
    $response = null;
    try {
        //  Invoke service function
        $response = JxcService::invokeApi($api, $method, $op_id, $request);
    } catch (Exception $e) {
        $response = array('status' => 'error', 'message' => $e->getMessage());
    }
    //  API invoked log.
    if (JxcConfig::$API_DEBUG_LOG) {
        $logIADao = new LogInvokeApiDao(JxcConfig::$DB_Config);
        $logIA = new LogInvokeApi();
        $logIA->service = $api;
        $logIA->request = json_encode($request);
        $logIA->response = json_encode($response);
        $logIA->op = $op_id;
        $logIADao->insert($logIA);
    }
    echo json_encode($response);
} catch (Exception $e) {
    exit(403);
}
