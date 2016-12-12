<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcConst;
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
use Jxc\Impl\Vo\VoCustomer;
use Jxc\Impl\Vo\VoOperator;
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
    public function getPdtList($voOp, $request) {
        $flag = isset($request['flag']) ? $request['flag'] : 0;
        $list = $this->productDao->selectAll($flag);
        $array = array();
        foreach ($list as $v) {
            if ($v instanceof VoProduct) {
                $array[] = $v->voToW2ui();
            }
        }
        return array('status' => 'success', 'records' => $array);
    }

    public function pdtW2gridRecords($voOp, $request) {
        $flag = isset($request['flag']) ? $request['flag'] : 0;
        $list = $this->productDao->selectAll($flag);
        $array = array();
        foreach ($list as $v) {
            if ($v instanceof VoProduct) {
                $array[] = $v->voToW2ui();
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
                    $voProduct->timeLastOp = DateUtil::makeTime();
                    $this->productDao->updateByFields($voProduct, array('pdt_name', 'pdt_color', 'pdt_price', 'timeLastOp'));
                    $updateAry[] = $voProduct->voToW2ui();
                }
            } else {    //  insert
                $voProduct = new VoProduct();
                $voProduct->w2uiToVo($change);
                $voProduct->datetime = DateUtil::makeTime();
                $voProduct->timeLastOp = DateUtil::makeTime();
                $voProduct = $this->productDao->insert($voProduct);
                $ua = $voProduct->voToW2ui();
                $ua['depId'] = $id;
                $updateAry[] = $ua;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    /**
     * 删除产品
     * @param $voOp
     * @param $request
     * @return array
     */
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
                    $updateAry[] = $voProduct->voToW2ui();
                }
            } else {    //  insert
                $voProduct = new VoProduct();
                $voProduct->w2uiToVo($change);
                $voProduct->datetime = DateUtil::makeTime();
                $voProduct = $this->productDao->insert($voProduct);
                $ua = $voProduct->voToW2ui();
                $ua->depId = $id;
                $updateAry[] = $ua;
            }
        }
        return array('status' => 'success', 'updates' => $updateAry);
    }

    /**
     * 进货
     * @param $voOp VoOperator
     * @param $request
     * @return array
     */
    public function procure($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes', 'op_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        //  库存数量检查
        $postData = array();
        $total_rmb = 0;
        foreach ($changes as $change) {
            if ($change['recid']) {
                $w2 = new W2PdtInfo();
                $w2->w2uiToVo($change);
                $postData[$w2->recid] = $w2;
                $total_rmb += $w2->calc_total_price();
            }
        }
        return $this->change(JxcConst::IO_TYPE_PROCURE, $voOp, $postData, $total_rmb, null);
    }

    public function procure2($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('op_target', 'op_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];


    }



    /**
     * 销售
     * @param $voOp
     * @param $request
     * @return array
     */
    public function sell($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes', 'ct_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $ct_id = $request['ct_id'];
        $voCustomer = $this->customerDao->selectByCtId($ct_id);
        if (!$voCustomer) {
            return array('status' => 'error', 'message' => "客户不存在: [{$ct_id}].");
        }
        //  库存数量检查
        $postData = array();
        $total_rmb = 0;
        foreach ($changes as $change) {
            if ($change['recid']) {
                $w2 = new W2PdtInfo();
                $w2->w2uiToVo($change);
                $postData[$w2->recid] = $w2;
                $total_rmb += $w2->calc_total_price();
            }
        }
        return $this->change(JxcConst::IO_TYPE_SALES, $voOp, $postData, $total_rmb, $voCustomer);
    }

    /**
     * 退货
     * @param $voOp
     * @param $request
     * @return array
     */
    public function refund($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('changes', 'ct_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $changes = $request['changes'];
        $ct_id = $request['ct_id'];
        $voCustomer = $this->customerDao->selectByCtId($ct_id);
        if (!$voCustomer) {
            return array('status' => 'error', 'message' => "客户不存在: [{$ct_id}].");
        }
        //  库存数量检查
        $postData = array();
        $total_rmb = 0;
        foreach ($changes as $change) {
            if ($change['recid']) {
                $w2 = new W2PdtInfo();
                $w2->w2uiToVo($change);
                $postData[$w2->recid] = $w2;
                $total_rmb += $w2->calc_total_price();
            }
        }
        return $this->change(JxcConst::IO_TYPE_REFUND, $voOp, $postData, $total_rmb, $voCustomer);
    }

    //  Private

    /**
     * 库存变更
     * @param int $type 订单类型    1:采购    2:销售    3:退货
     * @param VoOperator $voOp 操作员
     * @param array $postData 提交的数据
     * @param float $total_rmb 涉及金额
     * @param VoCustomer $voCustomer 客户信息
     * @return array
     */
    private function change($type, $voOp, $postData, $total_rmb, $voCustomer = null) {
        if (!$voOp || !is_array($postData)) {
            return array('status' => 'error', 'message' => "参数错误.");
        }
        //  订单日志
        $logOrder = new LogOrder();
        $logOrder->type = $type;
        $logOrder->status = JxcConst::STATUS_NORMAL;
        $logOrder->log_date = DateUtil::localDate();
        $logOrder->total_rmb = $total_rmb;
        $logOrder->datetime = DateUtil::makeTime();
        $logOrder->op_id = $voOp->op_id;
        $logOrder->op_name = $voOp->op_name;
        if ($voCustomer) {
            $logOrder->ct_id = $voCustomer->ct_id;
            $logOrder->ct_name = $voCustomer->ct_name;
        }
        $logOrder = $this->logOrderDao->insert($logOrder);
        //  更新库存
        $listOfPdt = $this->productDao->selectById(array_keys($postData));
        foreach ($listOfPdt as $kPdtId => $vPdt) {
            if (!isset($postData[$vPdt->pdt_id])) {
                continue;
            }
            $w2 = $postData[$vPdt->pdt_id];
            if ($vPdt instanceof VoProduct && $w2 instanceof W2PdtInfo) {
                foreach ($w2->pdt_counts as $kW2 => $vCount) {
                    if ($vCount && $vCount > 0) {
                        if ($type == JxcConst::IO_TYPE_SALES) {
                            if ($vCount && $vCount > 0 && $vPdt->pdt_counts[$kW2] < $vCount) {  //  库存数量检查
                                return array('status' => 'error', 'message' => "库存数量不足: 操作数量[{$vCount}]大于库存数量[{$vPdt->pdt_counts[$kW2]}]");
                            }
                            $vPdt->pdt_counts[$kW2] -= $vCount;
                        } else {
                            $vPdt->pdt_counts[$kW2] += $vCount;
                        }
                    }
                }
                $vPdt->pdt_total = $vPdt->calc_pdt_total();
                $vPdt->total_rmb = $vPdt->calc_total_price();
                $this->productDao->updateByFields($vPdt, array('pdt_counts', 'pdt_total', 'total_rmb'));
                //  详单日志
                $logOrderDetail = new LogOrderDetail();
                $logOrderDetail->convert($w2);
                $logOrderDetail->pdt_id = $kPdtId;
                $logOrderDetail->order_id = $logOrder->order_id;
                $logOrderDetail->pdt_total = $w2->calc_pdt_total();
                $logOrderDetail->total_rmb = $w2->calc_total_price();
                $this->logOrderDetailDao->insert($logOrderDetail);
            }
        }
        return array('status' => 'success');
    }
}