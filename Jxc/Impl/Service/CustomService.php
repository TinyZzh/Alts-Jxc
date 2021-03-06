<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogChargeDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\LogCharge;
use Jxc\Impl\Vo\VoCustomer;

/**
 * 会员服务
 */
final class CustomService extends JxcService {

    private $customDao;
    private $logChargeDao;

    public function __construct() {
        parent::__construct();
        $this->customDao = new CustomerDao(JxcConfig::$DB_Config);
        $this->logChargeDao = new LogChargeDao(JxcConfig::$DB_Config);
    }

    public function w2GetRecords($voOp, $request) {
        if (isset($request['search'])) {    //  搜索
            $records = $this->customDao->w2Search($request['search'], $request['searchLogic']);
            return array('status' => 'success', 'records' => $records);
        } else {
            $status = isset($request['status']) ? $request['status'] : 0;
            $data = $this->customDao->selectAll($status);
            $array = array();
            foreach ($data as $v) {
                $v->recid = $v->ct_id;
                $array[] = $v;
            }
            return array('status' => 'success', 'records' => $array);
        }
    }



    /**
     * 获取顾客列表
     * @param $voOp
     * @param $request
     * @return array
     */
    public function getCustomList($voOp, $request) {
        return array('state' => 1, 'data' => $this->customDao->w2uiSelect());
    }

    /**
     * 获取会员信息
     * @param $voOp
     * @param $request
     * @return array
     */
    public function records($voOp, $request) {
        $status = isset($request['status']) ? $request['status'] : 0;
        $data = $this->customDao->selectAll($status);
        $array = array();
        foreach ($data as $v) {
            $v->recid = $v->ct_id;
            $array[] = $v;
        }
        return array('status' => 'success', 'records' => $array);
    }

    /**
     * 更新会员信息
     * @param $voOp
     * @param $request
     * @return array
     */
    public function saveCustomerInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $ids = array_keys($aryId);
        $existMap = $this->customDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voCustomer = $existMap[$id];
                if ($voCustomer instanceof VoCustomer) {
                    $voCustomer->convert($change);
                    $this->customDao->updateByFields($voCustomer, array_keys($change));
                    $voCustomer->recid = $voCustomer->ct_id;
                    $updateAry[] = $voCustomer;
                }
            } else {    //  insert
                $voCustomer = new VoCustomer();
                $voCustomer->convert($change);
                $voCustomer = $this->customDao->insert($voCustomer);
                $voCustomer->recid = $voCustomer->ct_id;
                $voCustomer->depId = $id;
                $updateAry[] = $voCustomer;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    /**
     * 移除会员信息
     * @param $voOp
     * @param $request
     * @return array
     */
    public function removeCustomerInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('selected'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $status = isset($request['status']) ? $request['status'] : 0;
        if ($request['selected'] && count($request['selected']) > 0) {
            $sets = $this->customDao->selectById($request['selected'], $status);
            foreach ($sets as $k => $v) {
                if ($v instanceof VoCustomer) {
                    $v->status = (int)(!$status);
                    $this->customDao->updateByFields($v, array('status'));
                }
            }
        }
        return array('status' => 'success', 'deleted' => $request['selected']);
    }

    /**
     * 充值
     * @param $voOp
     * @param $request
     * @return array
     */
    public function charge($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('ct_id', 'money'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $operator = $request['op'];
        //  单笔充值100w上限
        $money = round($request['money'], 2);   //  四舍五入, 保留2位小数
        if ($money <= 0.0 || $money > 1000000.0) {
            return array('status' => 'error', 'message' => 'Charge money is out of range : ' . $money);
        }
        $vo = $this->customDao->selectByCtId($request['ct_id']);
        if ($vo) {
            $vo->ct_money += $money;
            //  日志
            $logCharge = new LogCharge();
            $logCharge->ct_id = $vo->ct_id;
            $logCharge->money = $vo->ct_money;
            $logCharge->datetime = DateUtil::makeTime();
            $logCharge->operator = $operator;
            $this->logChargeDao->insert($logCharge);
            //  保存充值
            $this->customDao->updateByFields($vo, array('ct_money'));
        }
        return array('status' => 'success');
    }

}