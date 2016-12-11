<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogOrderDao;
use Jxc\Impl\Dao\LogOrderDetailDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Util\GameUtil;

/**
 * 订单相关服务
 */
final class OrderService extends JxcService {

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
     * 获取订单列表
     * @param $voOp
     * @param $request
     * @return array
     */
    public function getOrderAll($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('type'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $type = $request['type'];
        $ct_id = isset($request['ct_id']) ? $request['ct_id'] : null;
        $pdt_id = isset($request['pdt_id']) ? $request['pdt_id'] : null;
        $map = $this->logOrderDao->w2uiSelectByCtIdAndPdtId($type, $ct_id, $pdt_id);
        return array('status' => 'success', 'records' => array_values($map));
    }


    public function getSalesOrder($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('type'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $type = $request['type'];
        $ct_id = isset($request['ct_id']) ? $request['ct_id'] : null;
        $pdt_id = isset($request['pdt_id']) ? $request['pdt_id'] : null;
        $map = $this->logOrderDao->w2uiSelectByCtIdAndPdtId($type, $ct_id, $pdt_id);
        return array('status' => 'success', 'records' => array_values($map));
    }


    /**
     * 获取订单详单信息
     * @param $voOp
     * @param $request
     * @return array
     */
    public function getOrderDetail($voOp, $request) {
        if ($verify = GameUtil::verifyRequestParams($request, array('order_id'))) {
            return array('status' => 'error', 'message' => 'Undefined field : ' . $verify);
        }
        $order_id = $request['order_id'];
        $details = $this->logOrderDetailDao->w2gridSelectByOrderId($order_id);
        return array('status' => 'success', 'records' => array_values($details));
    }


}