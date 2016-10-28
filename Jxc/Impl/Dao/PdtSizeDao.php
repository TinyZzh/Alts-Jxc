<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\VoPdtSize;
use Jxc\Impl\Vo\VoProduct;

/**
 * 产品尺码表
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class PdtSizeDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * @param $voPdtSize VoPdtSize
     * @return VoProduct
     * @throws Exception
     */
    public function insert($voPdtSize) {
        $query = $this->mysqlDB()->sqlInsert('tb_product_size', $voPdtSize->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        return $voPdtSize;
    }

    /**
     * @param $voPdtSize VoPdtSize
     * @param array $fields
     */
    public function updateByFields($voPdtSize, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_product_size', $voPdtSize->toArray($fields), array('pdt_id' => $voPdtSize->pdt_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $pdt_id  int  数据库唯一ID
     * @throws Exception
     */
    public function delete($pdt_id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_product_size', array('pdt_id' => $pdt_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }


    //

}