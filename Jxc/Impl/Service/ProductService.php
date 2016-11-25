<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogOrderDao;
use Jxc\Impl\Dao\LogOrderDetailDao;
use Jxc\Impl\Dao\OperatorDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Libs\W2UI;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\LogOrder;
use Jxc\Impl\Vo\LogOrderDetail;
use Jxc\Impl\Vo\VoProduct;
use Jxc\Impl\Vo\W2PdtInfo;

/**
 * 产品相关服务
 */
final class ProductService extends JxcService {

    private $productDao;
    private $customerDao;
    private $logOrderDao;
    private $logOrderDetailDao;

    public function __construct() {
        parent::__construct();
        $this->productDao = new ProductDao(JxcConfig::$DB_Config);
        $this->customerDao = new CustomerDao(JxcConfig::$DB_Config);
        $this->logOrderDao = new LogOrderDao(JxcConfig::$DB_Config);
        $this->logOrderDetailDao = new LogOrderDetailDao(JxcConfig::$DB_Config);
    }

    /**
     * 获取产品信息列表
     * @param $voOp
     * @param $request
     * @return array
     */
    private function getPdtList($voOp, $request) {
        $flag = isset($request['flag']) ? $request['flag'] : 0;
        $list = $this->productDao->selectAll($flag);
        $array = array();
        foreach ($list as $k => $v) {
            if ($v instanceof VoProduct) {
                $array[] = $v->voToW2ui($v);
            }
        }
        return array('status' => 'success', 'records' => $array);
    }

    /**
     * 保存产品信息
     * @param $request
     * @return array
     */
    public function savePdtInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $ids = array_keys($aryId);
        $existMap = $this->productDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voProduct = $existMap[$id];
                if ($voProduct instanceof VoProduct) {
                    $voProduct->w2uiToVo($change);
                    $this->productDao->updateByFields($voProduct);
                    $updateAry[] = $voProduct->voToW2ui($voProduct);
                }
            } else {    //  insert
                $voProduct = new VoProduct();
                $voProduct->w2uiToVo($change);
                $voProduct->datetime = DateUtil::makeTime();
                $voProduct = $this->productDao->insert($voProduct);
                $ua = $voProduct->voToW2ui($voProduct);
                $ua->depId = $id;
                $updateAry[] = $ua;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    public function removePdtInfo($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('selected'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $flag = isset($request['flag']) ? $request['flag'] : 0;
        if ($request['selected'] && count($request['selected']) > 0) {
            $sets = $this->productDao->selectById($request['selected'], $flag);
            $now = DateUtil::makeTime();
            foreach ($sets as $k => $v) {
                if ($v instanceof VoProduct) {
                    $v->flag = (int)(!$flag);   //  修改flag
                    $v->timeLastOp = $now;
                    $this->productDao->updateByFields($v, array('flag', 'timeLastOp'));
                }
            }
        }
        return array('status' => 'success', 'deleted' => $request['selected']);
    }

    /**
     * 采购
     */
    public function saveSalesOrder($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $ids = array_keys($aryId);
        $existMap = $this->productDao->selectById($ids);
        $updateAry = array();
        foreach ($changes as $change) {
            $id = $change['recid'];
            if (isset($existMap[$id])) {  //  update
                $voProduct = $existMap[$id];
                if ($voProduct instanceof VoProduct) {
                    $voProduct->w2uiToVo($change);
                    $this->productDao->updateByFields($voProduct);
                    $updateAry[] = $voProduct->voToW2ui($voProduct);
                }
            } else {    //  insert
                $voProduct = new VoProduct();
                $voProduct->w2uiToVo($change);
                $voProduct->datetime = DateUtil::makeTime();
                $voProduct = $this->productDao->insert($voProduct);
                $ua = $voProduct->voToW2ui($voProduct);
                $ua->depId = $id;
                $updateAry[] = $ua;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    public function getBaseShopInfoList($voOp, $request) {


    }

    /**
     * 进货
     * @param $request
     * @return array
     */
    public function procure($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes', 'op_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $op_id = $request['op_id'];
        //  权限检查
        $voOperator = $this->operatorDao->selectById($op_id);
        if (!$voOperator) {
            return array('status' => 'error', 'msg' => "操作员不存在: [{$op_id}].");
        }
        if (!isset($voOperator->op_auth['procure']) && !isset($voOperator->op_auth['all_allow'])) {
            return array('status' => 'error', 'msg' => "操作员权限不足: [{$op_id}] - Api : procure.");
        }

        //  库存数量检查
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $listOfPdt = $this->productDao->selectById(array_keys($aryId));
        $total_rmb = 0;
        //  库存变更
        foreach ($changes as $change) {
            $pdt_id = $change['recid'];
            if (!isset($listOfPdt[$pdt_id])) {
                return array('status' => 'error', 'msg' => "产品信息不存在: [{$change['recid']}].");
            }
            $w2 = new W2PdtInfo();
            $w2->w2uiToVo($change);
            $total_rmb += $w2->calc_total_price();

            $vo = $listOfPdt[$pdt_id];
            if ($vo instanceof VoProduct) {
                foreach ($w2->pdt_counts as $k => $count) {
                    $vo->pdt_counts[$k] += $count;
                }
                $vo->pdt_total = $vo->calc_pdt_total();
                $vo->total_rmb = $vo->calc_total_price();
                $this->productDao->updateByFields($vo, array('pdt_counts', 'pdt_total', 'total_rmb'));
            }
        }

        //  订单日志
        $logOrder = new LogOrder();
        $logOrder->op_id = $op_id;
        $logOrder->op_name = $voOperator->op_name;
        $logOrder->datetime = DateUtil::makeTime();
        $logOrder->total_rmb = $total_rmb;
        $logOrder = $this->logOrderDao->insert($logOrder);
        foreach ($changes as $change) {
            //  详单日志
            $logOrderDetail = new LogOrderDetail();
            $logOrderDetail->w2uiToVo($change);
            $logOrderDetail->order_id = $logOrder->order_id;
            $logOrderDetail->type = 2;  //  TODO: 订单类型
            $this->logOrderDetailDao->insert($logOrderDetail);
        }


//        return array('status' => 'success', 'updates' => $updateAry);


        return array('status' => 'success');
    }

    /**
     * 销售
     * @param $request
     * @return array
     */
    public function sell($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes', 'ct_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $ct_id = $request['ct_id'];

        //  库存数量检查
        $aryId = array();
        foreach ($changes as $change) {
            $aryId[$change['recid']] = 1;
        }
        $listOfPdt = $this->productDao->selectById(array_keys($aryId));
        $total_rmb = 0;
        foreach ($changes as $change) {
            $pdt_id = $change['recid'];
            if (!isset($listOfPdt[$pdt_id])) {
                return array('status' => 'error', 'msg' => "产品信息不存在: [{$change['recid']}].");
            }
            $vo = $listOfPdt[$pdt_id];
            //
            $w2 = new W2PdtInfo();
            $w2->w2uiToVo($change);
            $total_rmb += $w2->calc_total_price();
            foreach ($w2->pdt_counts as $k => $count) {
                if ($count > 0 && $count > $vo->pdt_counts[$k]) {
                    return array('status' => 'error', 'msg' => "库存数量不足: {$count}小于{$vo->pdt_counts[$k]}");
                }
            }
        }
        //  订单日志
        $logOrder = new LogOrder();
        $logOrder->ct_id = $ct_id;
        $logOrder->datetime = DateUtil::makeTime();
        $logOrder->total_rmb = $total_rmb;
        $logOrder = $this->logOrderDao->insert($logOrder);
        foreach ($changes as $change) {
            //  详单日志
            $logOrderDetail = new LogOrderDetail();
            $logOrderDetail->w2uiToVo($change);
            $logOrderDetail->order_id = $logOrder->order_id;
            $logOrderDetail->type = 1;  //  TODO: 订单类型
            $this->logOrderDetailDao->insert($logOrderDetail);
            //  库存变更


        }


//        return array('status' => 'success', 'updates' => $updateAry);


        return array('status' => 'success');
    }


}