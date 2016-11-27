<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogOrderDetail;

/**
 * 订单详细日志.
 */
class LogOrderDetailDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * 获取w2记录详单
     * @param $order_id
     * @return array
     */
    public function w2gridSelectByOrderId($order_id) {
        $query = "SELECT id,order_id,logD.`pdt_id`, logD.`pdt_counts`,logD.`pdt_price`,logD.`pdt_total`,logD.`pdt_zk`,logD.`total_rmb`,tb_p.`pdt_color`,tb_p.`pdt_name`
            FROM `log_order_detail` AS logD,`tb_product` AS tb_p WHERE logD.`pdt_id`=tb_p.`pdt_id` AND order_id={$order_id};";
//        $sets = $this->mysqlDB()->select('log_order_detail', '*', array('order_id' => $order_id));
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $data = array();
        foreach ($sets as $k => $v) {
            $logOrderDetail = new LogOrderDetail();
            $logOrderDetail->convert($v);
            $logOrderDetail->recid = $logOrderDetail->id;
            $logOrderDetail->pdt_name = $v['pdt_name'];
            $logOrderDetail->pdt_color = $v['pdt_color'];
            $data[$k] = $logOrderDetail->voToW2ui();
        }
        return $data;
    }

    public function select($id) {
        $datas = $this->mysqlDB()->select('log_order_detail', '*', array('id' => $id));
        if (!$datas) {
            return null;
        }
        $logOrderDetail = new LogOrderDetail();
        $logOrderDetail->convert($datas[0]);
        return $logOrderDetail;
    }

    public function selectByIds($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM log_order_detail WHERE id IN ({$inId});";
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $data) {
            $logOrderDetail = new LogOrderDetail();
            $logOrderDetail->convert($data);
            $map[$logOrderDetail->order_id] = $logOrderDetail;
        }
        return $map;
    }

    public function selectAll() {
        $query = $this->mysqlDB()->sqlSelectWhere('log_order_detail', '*');
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $array = array();
        foreach ($sets as $data) {
            $logOrderDetail = new LogOrderDetail();
            $logOrderDetail->convert($data);
            $array[$logOrderDetail->order_id] = $logOrderDetail;
        }
        return $array;
    }

    /**
     * @param $logOrderDetail LogOrderDetail
     * @return LogOrderDetail
     * @throws Exception
     */
    public function insert($logOrderDetail) {
        $query = $this->mysqlDB()->sqlInsert('log_order_detail', $logOrderDetail->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $logOrderDetail->id = $this->mysqlDB()->getInsertId();
        return $logOrderDetail;
    }


}