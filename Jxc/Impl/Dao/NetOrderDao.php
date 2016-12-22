<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogOrder;
use Jxc\Impl\Vo\VoNetOrder;

/**
 * 联网订单Dao
 * [审核功能]
 */
class NetOrderDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * @param $order_id
     * @param int $status
     * @return VoNetOrder|null
     */
    public function select($order_id, $status = 0) {
        $sets = $this->mysqlDB()->select('tb_order', '*', array('order_id' => $order_id, 'status' => $status));
        if (!$sets) {
            return null;
        }
        $order = new VoNetOrder();
        $order->convert($sets[0]);
        return $order;
    }

    /**
     * 根据审核人获取订单
     * @param $op_id
     * @param int $status
     * @return array
     */
    public function selectByOpId($op_id, $status = 0) {
        $sets = $this->mysqlDB()->select('tb_order', '*', array('op_id' => $op_id, 'status' => $status));
        $map = array();
        foreach ($sets as $data) {
            $order = new VoNetOrder();
            $order->convert($data);
            $map[$order->order_id] = $order;
        }
        return $map;
    }


    public function selectAll() {
        $query = $this->mysqlDB()->sqlSelectWhere('tb_order', '*', array());
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $array = array();
        foreach ($sets as $data) {
            $logOrder = new LogOrder();
            $logOrder->convert($data);
            $array[$logOrder->order_id] = $logOrder;
        }
        return $array;
    }

    /**
     * @param VoNetOrder $order
     * @return mixed
     */
    public function insert($order) {
        $query = $this->mysqlDB()->sqlInsert('tb_order', $order->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $order->order_id = $this->mysqlDB()->getInsertId();
        return $order;
    }

    /**
     * @param  $order VoNetOrder
     * @param array $fields
     */
    public function updateByFields($order, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_order', $order->toArray($fields), array('order_id' => $order->order_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $order_id  int  订单ID
     * @throws Exception
     */
    public function delete($order_id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_order', array('order_id' => $order_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }






}