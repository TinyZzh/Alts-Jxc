<?php
header("Content-type: text/html; charset=utf-8");
//  Object
include_once __DIR__ . "/AutoLoader.php";
include_once "./Impl/Core/JxcConfig.php";
Jxc\AutoLoader::register();
//  HTTP util
//include_once __DIR__ . "/Impl/Libs/Requests.php";
//Requests::register_autoloader();

if (!isset($_REQUEST['api'])) {
    exit('404');
}
$filePath = __DIR__ . "/Modules/" . $_REQUEST['api'] . '.php';
if (!file_exists($filePath)) {
    exit('404');
}
include_once $filePath;

