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
use \Jxc\Impl\Core\JxcConfig;
if (!isset($_REQUEST['api']) || !isset(JxcConfig::$JXC_SERVICE[$_REQUEST['api']])) {
    echo json_encode(array('state'=>-1, 'msg'=>'Unknown service.'));
    exit();
}
$api = $_REQUEST['api'];
if (!isset($_REQUEST['c'])) {
    echo json_encode(array('state'=>-1, 'msg'=>'Unknown command.'));
    exit();
}
$method = $_REQUEST['c'];
$request = $_REQUEST;
//  验证授权
//if (!in_array($api, JxcConfig::$PUBLIC)) {
//    if (!isset($_SESSION['op_account'])) {
//        echo json_encode(array('status'=>'error', 'msg'=>'Please login first.'));
//        exit();
//    }
//    if (!isset($_SESSION['op_auth']) ||
//        ($_SESSION['op_auth'] != 'all' && !isset($_SESSION['op_auth'][$method]))
//    ) {
//        echo json_encode(array('status'=>'error', 'msg'=>'Please login first.'));
//        exit();
//    }
//    $request['op'] = $_SESSION['op_id'];
//}

$clz = JxcConfig::$JXC_SERVICE[$api];
$service = new $clz();
if (method_exists($service, $method)) {
    $ref = new ReflectionMethod($clz, $method);
    if (!$ref->isPublic()) {
        echo json_encode(array('state'=>-1, 'msg'=>'The api is not public'));
        exit();
    }
} else {
    echo json_encode(array('state'=>-1, 'msg'=>'Unknown method.'));
    exit();
}

$response = $service->$method($request);
echo json_encode($response);

