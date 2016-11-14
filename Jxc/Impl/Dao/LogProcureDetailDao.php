<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogProcureOrder;
use Jxc\Impl\Vo\LogSales;

/**
 * 采购详单
 * Class LogProcureDao
 * @package Jxc\Impl\Dao
 */
class LogProcureDetailDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }


    public function selectByOrder($order) {

    }


    public function selectById($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM log_procure WHERE id IN ({$inId});";
        $datas = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($datas as $data) {
            $voLogSales = new LogSales();
            $voLogSales->convert($data);
            $map[$voLogSales->id] = $voLogSales;
        }
        return $map;
    }

    public function select($where = array()) {
        $resultSet = $this->mysqlDB()->select('log_procure', '*', $where);
        $array = array();
        foreach ($resultSet as $data) {
            $voLogSales = new LogSales();
            $voLogSales->convert($data);
            $array[] = $voLogSales;
        }
        return $array;
    }

    /**
     * @param $voLogSales LogSales
     * @return LogSales
     * @throws Exception
     */
    public function insert($voLogSales) {
        $query = $this->mysqlDB()->sqlInsert('log_procure', $voLogSales->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voLogSales->id = $this->mysqlDB()->getInsertId();
        return $voLogSales;
    }

    /**
     * @param $logProcure LogProcureOrder
     * @param array $fields
     */
    public function updateByFields($logProcure, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('log_procure', $logProcure->toArray($fields), array('id' => $logProcure->id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $id  int  数据库自增ID
     * @throws Exception
     */
    public function delete($id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('log_procure', array('id' => $id));
        $this->mysqlDB()->ExecuteSQL($query);
    }


    //

}