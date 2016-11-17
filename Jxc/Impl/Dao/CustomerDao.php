<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\VoCustomer;
use Jxc\Impl\Vo\VoProduct;

/**
 * 顾客信息Dao
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class CustomerDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }


    public function w2uiSelect($where = array()) {
        $sets = $this->mysqlDB()->select('tb_customer', '*', $where);
        $data = array();
        foreach ($sets as $k => $v) {
            $voCustomer = new VoCustomer();
            $voCustomer->convert($v);
            $data[] = array('id' => $k, 'text' => "[{$voCustomer->ct_id}]:{$voCustomer->ct_name}", 'ct_id' => $voCustomer->ct_id, 'ct_address' => $voCustomer->ct_address);
        }
        return $data;
    }

    public function selectById($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM tb_customer WHERE ct_id IN ({$inId});";
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $v) {
            $voCustomer = new VoCustomer();
            $voCustomer->convert($v);
            $map[$voCustomer->ct_id] = $voCustomer;
        }
        return $map;
    }

    public function selectCustomNameList() {
        $query = "SELECT ct_name FROM tb_customer;";
        $resultSet = $this->mysqlDB()->ExecuteSQL($query);
        return $resultSet;
    }

    public function select($where = array()) {
        $resultSet = $this->mysqlDB()->select('tb_customer', '*', $where);
        $array = array();
        foreach ($resultSet as $data) {
            $voCustomer = new VoCustomer();
            $voCustomer->convert($data);
            $array[] = $voCustomer;
        }
        return $array;
    }

    public function selectByCtId($ct_id) {
        $sets = $this->mysqlDB()->select('tb_customer', '*', array('ct_id' => $ct_id));
        if ($sets) {
            $voCustomer = new VoCustomer();
            $voCustomer->convert($sets[0]);
            return $voCustomer;
        }
        return null;
    }

    /**
     * @param $voCustomer VoCustomer
     * @return VoCustomer
     * @throws Exception
     */
    public function insert($voCustomer) {
        $query = $this->mysqlDB()->sqlInsert('tb_customer', $voCustomer->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voCustomer->ct_id = $this->mysqlDB()->getInsertId();
        return $voCustomer;
    }

    /**
     * @param $voCustomer VoCustomer
     * @param array $fields
     */
    public function updateByFields($voCustomer, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_customer', $voCustomer->toArray($fields), array('ct_id' => $voCustomer->ct_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $ct_id  int  顾客唯一ID
     * @throws Exception
     */
    public function delete($ct_id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_customer', array('ct_id' => $ct_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }


    //

}