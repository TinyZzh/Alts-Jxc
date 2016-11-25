<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogChargeDao;
use Jxc\Impl\Dao\OperatorDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\LogCharge;
use Jxc\Impl\Vo\VoCustomer;

class PublicService extends JxcService {

    private $operatorDao;

    public function __construct() {
        parent::__construct();
        $this->operatorDao = new OperatorDao(JxcConfig::$DB_Config);
    }

    /**
     * 登录管理系统
     * @param $request
     * @return array
     */
    public function login($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('account', 'psw'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $voOperator = $this->operatorDao->selectByAccount($request['account']);
        if (!$voOperator) {
            return array('status' => 'error', 'message' => 'Unknown operator : ' . $request['account']);
        }
        $_SESSION['op_id'] = $voOperator->op_id;
        $_SESSION['op_account'] = $voOperator->op_account;
        $_SESSION['op_auth'] = json_decode($voOperator->op_auth);  //  TODO:   权限管理
        return array('status' => 'success');
    }

    public function logout($voOp, $request) {

    }

}