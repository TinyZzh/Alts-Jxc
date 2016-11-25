<?php

namespace Jxc\Impl\Core;

use Jxc\Impl\Dao\OperatorDao;
use ReflectionMethod;

/**
 * Service Interface.
 * Class JxcService
 * @package Jxc\Impl\Core
 */
class JxcService {

    public static $PUBLIC_FUNC = array();

    protected $operatorDao;

    public function __construct() {
        $this->register();
        $this->operatorDao = new OperatorDao(JxcConfig::$DB_Config);
    }

    private function register() {
        //  register method
        $methods = get_class_methods(get_class($this));
        foreach ($methods as $k => $v) {
            $PUBLIC_FUNC[$k] = $v;
        }
    }

    /**
     * 调用接口
     * @param $clz  string 注册的Service
     * @param $method   string  接口名称
     * @param $op_id    int 操作员ID
     * @param $params   array   请求参数
     * @return array|mixed
     */
    public function invoke($clz, $method, $op_id, $params) {
        if (method_exists($this, $method)) {
            $ref = new ReflectionMethod($clz, $method);
            if (!$ref->isPublic()) {
                return array('status' => 'error', 'message' => 'The api is not public');
            }
        } else {
            return array('status' => 'error', 'message' => 'Unknown method.');
        }
        //  检查权限
        $voOperator = null;
        if (!in_array($clz, JxcConfig::$PUBLIC)) {
            if ($op_id <= 0) {
                return array('status' => 'error', 'message' => 'Unknown method.');
            }
            $voOperator = $this->operatorDao->selectById($op_id);
            if (!$voOperator) {
                return array('status' => 'error', 'message' => "操作员不存在: [{$op_id}].");
            }
            if (!isset($voOperator->op_auth['procure']) && !isset($voOperator->op_auth['all_allow'])) {
                return array('status' => 'error', 'message' => "操作员权限不足: [{$op_id}] - Api : procure.");
            }
        }
        if (!is_callable(array($this, $method))) {
            return array('status' => 'error', 'message' => "未知接口: [{$method}].");
        }
        return call_user_func_array(array($this, $method), array($voOperator, $params));
    }


}