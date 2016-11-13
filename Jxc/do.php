<?php
header("Content-type: text/html; charset=utf-8");
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
if (!isset($_REQUEST['c'])) {
    echo json_encode(array('state'=>-1, 'msg'=>'Unknown command.'));
    exit();
}
$clz = JxcConfig::$JXC_SERVICE[$_REQUEST['api']];
$method = $_REQUEST['c'];
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

$response = $service->$method($_REQUEST);
echo json_encode($response);

