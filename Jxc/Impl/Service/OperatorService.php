<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcConst;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogChargeDao;
use Jxc\Impl\Dao\OperatorDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\LogCharge;
use Jxc\Impl\Vo\VoCustomer;
use Jxc\Impl\Vo\VoOperator;

/**
 * 操作员服务
 */
final class OperatorService extends JxcService {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 添加新操作员
     * @param VoOperator $voOp
     * @param $request
     * @return array
     */
    public function opCreate($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('op_account', 'op_psw', 'op_name', 'op_phone', 'op_auth'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $voOperator = $this->operatorDao->selectByAccount($request['account']);
        if ($voOperator) {
            return array('status' => 'error', 'message' => "账号已存在. 无法重复添加.Account : [{$request['account']}]");
        }
        $authMap = $request['op_auth'];
        if (!$authMap || !is_array($authMap) || count($authMap) <= 0) {
            return array('status' => 'error', 'message' => "权限设置不能为空.");
        }
        $voOperator = new VoOperator();
        $voOperator->op_account = $request['op_account'];
        $voOperator->op_psw = $request['op_psw'];
        $voOperator->op_name = $request['op_name'];
        $voOperator->op_phone = $request['op_phone'];
        foreach ($authMap as $method => $val) {
            if ($val && $this->verifyAuth($voOp, $method)) {
                return array('status' => 'error', 'message' => "无法修改, 操作权限不足.");
            }
            $voOperator->op_auth[$method] = $val;
        }
        $voOperator = $this->operatorDao->insert($voOperator);
        return array('status' => 'success');
    }

    /**
     * 获取个人信息
     * @param VoOperator $voOp
     * @param $request
     * @return array
     */
    public function getSelfOperator($voOp, $request) {
        $voOp->recid = $voOp->op_id;
        return array('status' => 'success', 'record' => $voOp);
    }

    public function w2GetSelfOperator($voOp, $request) {
        $voOp->recid = $voOp->op_id;
        return array('status' => 'success', 'recid' => $voOp->op_id, 'record' => $voOp);
    }

    public function w2OpChangeAuth($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('record'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        return $this->opChangeAuth($voOp, $request['record']);
    }

    public function w2OpChangeSelfInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('record'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        return $this->opChangeSelfInfo($voOp, $request['record']);
    }

    public function w2OpChangeSelfPsw($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('record'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        return $this->opChangeSelfPsw($voOp, $request['record']);
    }

    /**
     * 改变操作员状态
     * @param $voOp
     * @param $request
     * @return array
     */
    public function opChangeStatus($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('op_account'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $voOperator = $this->operatorDao->selectByAccount($request['op_account']);
        if (!$voOperator) {
            return array('status' => 'error', 'message' => "操作员信息不存在: [{$request['account']}].");
        }
        $voOperator->status = !$voOperator->status;
        $this->operatorDao->updateByFields($voOperator, array('status'));
        return array('status' => 'success');
    }

    /**
     * 改变操作员权限
     * @param VoOperator $voOp
     * @param $request
     * @return array
     */
    public function opChangeAuth($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('op_account', 'op_auth'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $authMap = $request['op_auth'];
        if ($voOp->op_account == $request['op_auth']) {
            return array('status' => 'error', 'message' => "无法修改自己的权限.");
        }
        $voOperator = $this->operatorDao->selectByAccount($request['op_account']);
        if (!$voOperator) {
            return array('status' => 'error', 'message' => "操作员不存在: [{$request['op_account']}].");
        }
        foreach ($authMap as $method => $val) {
            if ($val && $this->verifyAuth($voOp, $method)) {
                return array('status' => 'error', 'message' => "无法修改, 操作权限不足.");
            }
            $voOperator->op_auth[$method] = $val;
        }
        $this->operatorDao->updateByFields($voOperator, array('op_auth'));
        return array('status' => 'success');
    }

    /**
     * 修改密码
     * @param VoOperator $voOp
     * @param $request
     * @return array
     */
    public function opChangeSelfPsw($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('psw_old', 'psw_new'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $psw = $request['psw_new'];
        if (count($psw) <= 0) {
            return array('status' => 'error', 'message' => "密码长度错误.");
        }
        if (($request['psw_old'] == $request['psw_new']) || $voOp->op_psw != $request['psw_old']) {
            return array('status' => 'error', 'message' => "修改失败, 密码错误.");
        }
        $voOp->op_psw = $psw;
        $this->operatorDao->updateByFields($voOp, array('op_psw'));
        return array('status' => 'success');
    }

    public function opChangeSelfInfo($voOp, $request) {
        $fields = array();
        $params = array('op_account', 'op_name', 'op_phone');
        foreach ($params as $val) {
            if (isset($request[$val])) {
                $voOp->$val = $request[$val];
                $fields[] = $val;
            }
        }
        if ($fields) {
            $this->operatorDao->updateByFields($voOp, $fields);
        }
        return array('status' => 'success');
    }

    //  修改其他人 - 仅限于Admin

    /**
     * [*]修改其他用户信息. 仅限于超级用户权限
     * @param $voOp
     * @param $request
     * @return array
     */
    public function opChangeOtherInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('op_account'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        if (!$this->isSuperAuth($voOp)) {
            return array('status' => 'error', 'message' => "[*]无法修改, 操作权限不足.");
        }
        $voOperator = $this->operatorDao->selectByAccount($request['op_account']);
        if (!$voOperator) {
            return array('status' => 'error', 'message' => "操作员不存在: [{$request['op_account']}].");
        }
        $fields = array();
        $params = array('op_account', 'op_name', 'op_phone');
        foreach ($params as $val) {
            if (isset($request[$val])) {
                $f = $request[$val];
                $voOperator->$f = $request[$val];
                $fields[] = $f;
            }
        }
        if ($fields) {
            $this->operatorDao->updateByFields($voOperator, $fields);
        }
        return array('status' => 'success');
    }

    /**
     * 超级管理员权限
     * @param VoOperator $voOp
     * @return bool
     */
    private function isSuperAuth($voOp) {
        return (isset($voOp->op_auth[JxcConst::SYSTEM_AUTHORITY_ALL]) && $voOp->op_auth[JxcConst::SYSTEM_AUTHORITY_ALL]);
    }

    /**
     * 检查权限.
     * 要修改其他操作员的权限，自身权限必须大于且包含这些权限. 否则无法操作成功.
     * @param VoOperator $voOp 操作员
     * @param string $func 接口名
     * @return bool
     */
    private function verifyAuth($voOp, $func) {
        if ($this->isSuperAuth($voOp)) {
            return true;
        } else {
            if (isset($voOp->op_auth[$func]) && $voOp->op_auth[$func]) {
                return true;
            }
        }
        return false;
    }
}