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
use Jxc\Impl\Vo\VoOperator;

class OperatorService extends JxcService {

    private $operatorDao;

    public function __construct() {
        parent::__construct();
        $this->operatorDao = new OperatorDao(JxcConfig::$DB_Config);
    }

    /**
     * 添加操作员
     * @param $request
     * @return array
     */
    public function addOperator($request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('account', 'psw', 'name', 'auth'))) {
            return array('status' => 'error', 'msg' => 'Undefined field : ' . $verify);
        }
        $voOperator = $this->operatorDao->selectByAccount($request['account']);
        if ($voOperator) {
            return array('status' => 'error', 'msg' => '操作员已经存在' . $request['account']);
        }
        $voOperator = new VoOperator();
        $voOperator->op_account = $request['account'];
        $voOperator->op_psw = $request['psw'];
        $voOperator->op_name = $request['name'];
        $voOperator->op_auth = $request['auth'];
        $this->operatorDao->insert($voOperator);
        //  日志

        return array('status' => 'success');
    }

    public function delOperator($request) {

    }

}