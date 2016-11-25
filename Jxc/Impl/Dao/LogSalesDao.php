<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogSales;

/**
 * 销售日志
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class LogSalesDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    public function select($id) {
        $datas = $this->mysqlDB()->select('log_sales', '*', array('id' => $id));
        if (!$datas) {
            return null;
        }
        $voLogSales = new LogSales();
        $voLogSales->convert($datas[0]);
        return $voLogSales;
    }

    public function selectById($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM log_sales WHERE id IN ({$inId});";
        $datas = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($datas as $data) {
            $voLogSales = new LogSales();
            $voLogSales->convert($data);
            $map[$voLogSales->id] = $voLogSales;
        }
        return $map;
    }

    public function selectAll() {
        $query = $this->mysqlDB()->sqlSelectWhere('log_sales', '*');
        $datas = $this->mysqlDB()->ExecuteSQL($query);
        $array = array();
        foreach ($datas as $data) {
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
        $query = $this->mysqlDB()->sqlInsert('log_sales', $voLogSales->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voLogSales->id = $this->mysqlDB()->getInsertId();
        return $voLogSales;
    }

    /**
     * @param $voLogSales LogSales
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


    //  sales order

    public function insertOrder($vo) {
        $query = $this->mysqlDB()->sqlInsert('log_sales', $voLogSales->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voLogSales->id = $this->mysqlDB()->getInsertId();
        return $voLogSales;
    }









}