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
        $datas = $this->mysqlDB()->select('log_order', '*', array('id' => $id));
        if (!$datas) {
            return null;
        }
        $logOrder = new LogOrder();
        $logOrder->convert($datas[0]);
        return $logOrder;
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
        $query = $this->mysqlDB()->sqlSelectWhere('log_order', '*');
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