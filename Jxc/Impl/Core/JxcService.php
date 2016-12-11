<?php

namespace Jxc\Impl\Core;

use Jxc\Impl\Dao\LogInvokeApiDao;
use Jxc\Impl\Dao\OperatorDao;
use Jxc\Impl\Vo\LogInvokeApi;
use ReflectionMethod;

/**
 * Service Interface.
 * Class JxcService
 * @package Jxc\Impl\Core
 */
class JxcService {

    protected $operatorDao;

    public function __construct() {
        $this->operatorDao = new OperatorDao(JxcConfig::$DB_Config);
    }

    protected function initialize() {
        // do something
    }

    //  Static

    /**
     * 收保护函数
     */
    protected static $PROTECTED = array(
        '__construct' => 1,
        'invokeApi' => 1,
        'isProtected' => 1,
    );

    public static function isProtected($clz, $func) {
        if (isset(self::$PROTECTED[$func])) {
            return true;
        }
        $ref = new ReflectionMethod($clz, $func);
        return !$ref->isPublic();
    }

    /**
     * 调用接口
     * @param string $api
     * @param $method   string  接口名称
     * @param $op_id    int 操作员ID
     * @param $params   array   请求参数
     * @return array|mixed
     */
    public static function invokeApi($api, $method, $op_id, $params) {
        $clz = JxcConfig::$JXC_SERVICE[$api];
        $service = new $clz();
        if ($service instanceof JxcService) {
            if (method_exists($clz, $method)) {
                if (self::isProtected($clz, $method)) {
                    return array('status' => 'error', 'message' => 'The api is not public');
                }
            } else {
                return array('status' => 'error', 'message' => 'Unknown method.');
            }
            //  检查权限
            $voOperator = null;
            if (!in_array($api, JxcConfig::$PUBLIC)) {
                if ($op_id <= 0) {
                    return array('status' => 'error', 'message' => '未经授权的访问.');
                }
                $voOperator = $service->operatorDao->selectById($op_id);
                if (!$voOperator && !$voOperator->op_auth) {
                    return array('status' => 'error', 'message' => "操作员信息错误: [{$op_id}].\n 请联系系统管理员.");
                }
                if (isset($voOperator->op_auth[JxcConst::SYSTEM_AUTHORITY_ALL])
                    && $voOperator->op_auth[JxcConst::SYSTEM_AUTHORITY_ALL]) {
                    //  no-op
                } else {
                    if (isset($voOperator->op_auth[$method]) && $voOperator->op_auth[$method]) {
                        //  no-op
                    } else {
                        return array('status' => 'error', 'message' => "无法执行此操作!. 权限不足: [{$voOperator->op_name}] - Api:[{$method}]. \n 请联系系统管理员.");
                    }
                }
            }
            if (!is_callable(array($service, $method))) {
                return array('status' => 'error', 'message' => "未知接口: [{$method}].");
            }
            return call_user_func_array(array($service, $method), array($voOperator, $params));
        } else {
            return array('status' => 'error', 'message' => 'Unknown service.');
        }
    }
}