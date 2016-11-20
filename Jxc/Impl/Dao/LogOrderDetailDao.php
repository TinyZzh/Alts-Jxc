<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogOrderDetail;

/**
 * 订单详细日志
 */
class LogOrderDetailDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
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

    public function selectById($ids) {
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