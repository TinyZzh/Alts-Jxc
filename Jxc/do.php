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

if (!isset($_REQUEST['api']) || !isset(JxcConfig::$JXC_SERVICE[$_REQUEST['api']])) {
    echo json_encode(array('state' => -1, 'msg' => 'Unknown service.'));
    exit();
}
$api = $_REQUEST['api'];
if (!isset($_REQUEST['c'])) {
    echo json_encode(array('state' => -1, 'msg' => 'Unknown command.'));
    exit();
}
$method = $_REQUEST['c'];
$request = $_REQUEST;
$op_id = isset($request['op']) ? $request['op'] : 1;
$clz = JxcConfig::$JXC_SERVICE[$api];

$service = new $clz();
if ($service instanceof JxcService) {
    $response = $service->invoke($clz, $method, $op_id, $request);
    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'msg' => 'Unknown service.'));
    exit();
}

