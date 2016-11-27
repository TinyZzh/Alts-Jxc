<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogOrder;

/**
 * 订单日志
 */
class LogOrderDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    public function select($id) {
        $sets = $this->mysqlDB()->select('log_order', '*', array('id' => $id));
        if (!$sets) {
            return null;
        }
        $logOrder = new LogOrder();
        $logOrder->convert($sets[0]);
        return $logOrder;
    }

    public function selectByCtId($ct_id) {
        $sets = $this->mysqlDB()->select('log_order', '*', array('ct_id' => $ct_id));
        $map = array();
        foreach ($sets as $data) {
            $logOrder = new LogOrder();
            $logOrder->convert($data);
            $map[$logOrder->order_id] = $logOrder;
        }
        return $map;
    }

    /**
     *
     * @param $ct_id
     * @param $pdt_id
     * @return array
     * @throws Exception
     */
    //  SELECT * FROM log_order WHERE EXISTS (SELECT * FROM log_order_detail WHERE log_order.order_id=log_order_detail.order_id AND log_order.ct_id={} AND log_order_detail.pdt_id={}) ORDER BY `datetime`
    public function w2uiSelectByCtIdAndPdtId($ct_id, $pdt_id) {
        $query = '';
        if ($ct_id) {
            $var1 = "SELECT * FROM log_order_detail WHERE log_order.order_id=log_order_detail.order_id AND log_order.ct_id={$ct_id}";
            if ($pdt_id) {
                $var1 .= " AND log_order_detail.pdt_id={$pdt_id}";
            }
            $query = "SELECT * FROM log_order WHERE EXISTS ({$var1}) ORDER BY `datetime` DESC";
        } else {
            $query = $this->mysqlDB()->sqlSelectWhere('log_order', '*', array());
        }
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $data) {
            $logOrder = new LogOrder();
            $logOrder->convert($data);
            $logOrder->recid = $logOrder->order_id;
            $map[$logOrder->order_id] = $logOrder;
        }
        return $map;
    }

    public function selectById($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM log_order WHERE id IN ({$inId});";
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $data) {
            $logOrder = new LogOrder();
            $logOrder->convert($data);
            $map[$logOrder->order_id] = $logOrder;
        }
        return $map;
    }

    public function selectAll() {
        $query = $this->mysqlDB()->sqlSelectWhere('log_order', '*', array());
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
     * @param $logOrder LogOrder
     * @return LogOrder
     * @throws Exception
     */
    public function insert($logOrder) {
        $query = $this->mysqlDB()->sqlInsert('log_order', $logOrder->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $logOrder->order_id = $this->mysqlDB()->getInsertId();
        return $logOrder;
    }













}