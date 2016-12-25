<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcConst;
use Jxc\Impl\Core\JxcFlow;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogOrderDao;
use Jxc\Impl\Dao\LogOrderDetailDao;
use Jxc\Impl\Dao\NetOrderDao;
use Jxc\Impl\Dao\OperatorDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Libs\W2UI;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\LogOrder;
use Jxc\Impl\Vo\LogOrderDetail;
use Jxc\Impl\Vo\VoCustomer;
use Jxc\Impl\Vo\VoNetOrder;
use Jxc\Impl\Vo\VoNetOrderDetail;
use Jxc\Impl\Vo\VoOperator;
use Jxc\Impl\Vo\VoProduct;
use Jxc\Impl\Vo\W2PdtInfo;

/**
 * 审核相关服务
 */
final class NetOrderService extends JxcService {

    private $productDao;
    private $onlineOrderDao;

    public function __construct() {
        parent::__construct();
        $this->productDao = new ProductDao(JxcConfig::$DB_Config);
        $this->onlineOrderDao = new NetOrderDao(JxcConfig::$DB_Config);
    }

    /**
     * 获取等待审核的订单信息
     * @param $voOp VoOperator
     * @param $request
     * @return array
     */
    public function getPendingOrder($voOp, $request) {
        $map = $this->onlineOrderDao->selectByOpId($voOp->op_id);

        return array('status' => 'success', 'records' => $map);
    }

    public function getOrderDetail($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('ids'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $ids = explode(',', $request['ids']);
        if (count($ids) <= 0) {
            return array('status' => 'error', 'message' => 'ids is null');
        }
        $array = $this->loadOrderDetail($ids, $voOp->op_id, false);
        return array('status' => 'success', 'records' => $array);
    }

    public function getSmallOrderDetail($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('ids'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $ids = explode(',', $request['ids']);
        if (count($ids) <= 0) {
            return array('status' => 'error', 'message' => 'ids is null');
        }
        $array = $this->loadOrderDetail($ids, $voOp->op_id, true);
        return array('status' => 'success', 'records' => $array);
    }

    /**
     * 订单校验和确认
     * @param $voOp VoOperator
     * @param $request
     * @return array
     */
    public function verifyOrder($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('order_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $order_id = $request['order_id'];
        $order = $this->onlineOrderDao->select($order_id, JxcConst::STATUS_NORMAL);
        if (!$order) {
            return array('status' => 'error', 'message' => "订单信息[ID:{$order_id}]不存在或以废弃");
        }
        if ($order->completed) {
            return array('status' => 'error', 'message' => "订单状态已完结 - 不能修改和操作");
        }
        if (!$this->isSuperAuth($voOp) && $voOp->op_id != $order->op_id) {
            return array('status' => 'error', 'message' => "权限不足. 指定审核人为[ID:{$order->op_id}, Name:{$order->op_name}]");
        }
        $target = $request['target'];
        $targetOp = $this->operatorDao->selectById($target);
        if (!$targetOp) {   //  经办人不存在
            return array('status' => 'error', 'message' => "指定的审核人[{$target}]不存在");
        }
        if (!$this->verifyAuth($targetOp, 'verifyOrder')) {
            return array('status' => 'error', 'message' => "指定的审核人[{$target}]权限不足");
        }
        $nextStep = $order->step + 1;
        if (isset(JxcFlow::$FLOW_PROCURE[$nextStep])) {
            $order->step = $nextStep;
            $order->op_id = $voOp->op_id;
            $order->op_name = $voOp->op_name;
            $this->onlineOrderDao->updateByFields($order, array('step'));
        } else {
            $order->completed = true;
            $this->onlineOrderDao->updateByFields($order, array('completed'));
        }
        return array('status' => 'success');
    }

    //  Private

    /**
     * 加载订单信息
     * @param $op_id
     * @param $ids
     * @param bool $little
     * @return array
     */
    private function loadOrderDetail($op_id, $ids, $little = false) {
        $map = $this->onlineOrderDao->selectByOpId($op_id);
        $array = array();
        foreach ($ids as $id) {
            if (!isset($map[$id])) {
                $order = $map[$id];
                if ($order instanceof VoNetOrderDetail) {
                    if ($little) {
                        unset($order->pdt_price);
                        unset($order->total_rmb);
                        unset($order->pdt_zk);
                    }
                    $array[$id] = $order;
                }
            }
        }
        return $array;
    }

}