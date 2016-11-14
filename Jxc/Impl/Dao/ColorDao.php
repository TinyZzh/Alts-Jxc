<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\VoColor;
use Jxc\Impl\Vo\VoProduct;

/**
 * 颜色信息Dao
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class ColorDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    public function w2uiSelectAll() {
        $sets = $this->mysqlDB()->select('tb_colors', '*', array());
        $data = array();
        foreach ($sets as $k => $v) {
            $data[] = array('id' => $k, 'text' => $v);
        }
        return $data;
    }

    /**
     * @param $voColor VoColor
     * @return VoColor
     * @throws Exception
     */
    public function insert($voColor) {
        $query = $this->mysqlDB()->sqlInsert('tb_colors', $voColor->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voColor->color_id = $this->mysqlDB()->getInsertId();
        return $voColor;
    }

    /**
     * @param $voColor VoColor
     * @param array $fields
     */
    public function updateByFields($voColor, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_colors', $voColor->toArray($fields), array('ct_id' => $voColor->color_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $color_id  int  颜色ID
     * @throws Exception
     */
    public function delete($color_id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_colors', array('ct_id' => $color_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }


    //

}