<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\VoLogSales;
use Jxc\Impl\Vo\VoPdtSize;
use Jxc\Impl\Vo\VoProduct;

/**
 * 销售日志
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class LogSalesDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * @param $voLogSales VoLogSales
     * @return VoLogSales
     * @throws Exception
     */
    public function insert($voLogSales) {
        $query = $this->mysqlDB()->sqlInsert('log_sales', $voLogSales->toArray());
        $voLogSales->id = $this->mysqlDB()->getInsertId();
        $this->mysqlDB()->ExecuteSQL($query);
        return $voLogSales;
    }

    /**
     * @param $voLogSales VoLogSales
     * @param array $fields
     */
    public function updateByFields($voLogSales, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('log_sales', $voLogSales->toArray($fields), array('id' => $voLogSales->id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $id  int  数据库自增ID
     * @throws Exception
     */
    public function delete($id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('log_sales', array('id' => $id));
        $this->mysqlDB()->ExecuteSQL($query);
    }










    

    //

}