<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogCharge;

/**
 * 充值日志Dao
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class LogChargeDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    public function selectAll() {
        $resultSet = $this->mysqlDB()->select('log_procure', '*', array());
        $array = array();
        foreach ($resultSet as $data) {
            $voLogSales = new LogCharge();
            $voLogSales->convert($data);
            $array[] = $voLogSales;
        }
        return $array;
    }

    public function selectByCtId($ct_id) {
        $resultSet = $this->mysqlDB()->select('log_procure', '*', array('ct_id' => $ct_id));
        $array = array();
        foreach ($resultSet as $data) {
            $voLogSales = new LogCharge();
            $voLogSales->convert($data);
            $array[] = $voLogSales;
        }
        return $array;
    }

    /**
     * @param $logCharge LogCharge
     * @return LogCharge
     * @throws Exception
     */
    public function insert($logCharge) {
        $query = $this->mysqlDB()->sqlInsert('log_charge', $logCharge->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $logCharge->id = $this->mysqlDB()->getInsertId();
        return $logCharge;
    }
}