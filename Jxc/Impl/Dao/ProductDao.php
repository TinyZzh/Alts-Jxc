<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Libs\W2UI;
use Jxc\Impl\Vo\VoProduct;

/**
 * 产品库存表
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class ProductDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    public function w2uiSelectAll($flag = 0) {
        $query = $this->mysqlDB()->sqlSelectWhere('tb_product', '*', array('flag' => $flag));
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $array = array();
        foreach ($sets as $k => $data) {
            $voProduct = new VoProduct();
            $voProduct->convert($data);
            $array[$voProduct->pdt_id] = W2UI::objToW2ui($voProduct);
            $array[$voProduct->pdt_id]['pdt_id'] = array('id' => $k, 'text' => $voProduct->pdt_id); //  w2Field => list
        }
        return $array;
    }

    public function selectById($ids, $flag = 0) {
        $inId = "'" . implode("','", $ids) . "'";
        $query = "SELECT * FROM tb_product WHERE pdt_id IN ({$inId}) AND flag={$flag};";
        $datas = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($datas as $data) {
            $voProduct = new VoProduct();
            $voProduct->convert($data);
            $map[$voProduct->pdt_id] = $voProduct;
        }
        return $map;
    }

    /**
     * @return array|bool|int
     * @throws Exception
     * @deprecated
     */
    public function selectPdtIdList() {
        $query = "SELECT pdt_id FROM tb_product;";
        $resultSet = $this->mysqlDB()->ExecuteSQL($query);
        return $resultSet;
    }

    /**
     * @param int $flag
     * @return array
     * @throws Exception
     */
    public function selectAll($flag = 0) {
        $query = $this->mysqlDB()->sqlSelectWhere('tb_product', '*', array('flag' => $flag));
        $datas = $this->mysqlDB()->ExecuteSQL($query);
        $array = array();
        foreach ($datas as $data) {
            $voProduct = new VoProduct();
            $voProduct->convert($data);
            $array[] = $voProduct;
        }
        return $array;
    }

    /**
     * @param $voProduct VoProduct
     * @return VoProduct
     * @throws Exception
     */
    public function insert($voProduct) {
        $query = $this->mysqlDB()->sqlInsert('tb_product', $voProduct->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
//        $voProduct->pdt_id = $this->mysqlDB()->getInsertId();
        return $voProduct;
    }

    /**
     * @param $voProduct VoProduct
     * @param array $fields
     */
    public function updateByFields($voProduct, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_product', $voProduct->toArray($fields), array('pdt_id' => $voProduct->pdt_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $id  int  数据库唯一ID
     * @throws Exception
     */
    public function delete($id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_product', array('pdt_id' => $id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    //  废弃的产品



}