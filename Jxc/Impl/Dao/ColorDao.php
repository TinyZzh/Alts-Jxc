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
            $data[] = array('id' => $k, 'text' => $v['color_name'], 'color_id' => $v['color_id'], 'color_rgba' => $v['color_rgba']);
        }
        return $data;
    }

    public function w2uiSelectColorByPdtIds($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM tb_color WHERE color_id IN (SELECT pdt_color FROM tb_product WHERE pdt_id IN ({$inId}));";
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $v) {
            $voColor = new VoColor();
            $voColor->convert($v);
            $map[$voColor->color_id] = $voColor;
        }
        return $map;
    }

    public function selectById($ids) {
        $inId = implode(",", $ids);
        $query = "SELECT * FROM tb_colors WHERE color_id IN ({$inId});";
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $v) {
            $voColor = new VoColor();
            $voColor->convert($v);
            $map[$voColor->color_id] = $voColor;
        }
        return $map;
    }

    public function w2gridRecords() {
        $sets = $this->mysqlDB()->select('tb_colors', '*', array());
        $data = array();
        foreach ($sets as $k => $v) {
            $voColor = new VoColor();
            $voColor->convert($v);
            //  5个一组
            $i = $k % 5;
            $j = floor($k / 5);
            $data[$j]['recid'] = $j;
            $data[$j]['color'.$i] = $voColor->color_id;
        }
        return $data;
    }

    public function selectAll() {
        $sets = $this->mysqlDB()->select('tb_colors', '*', array());
        $data = array();
        foreach ($sets as $k => $v) {
            $voColor = new VoColor();
            $voColor->convert($v);
            $data[$voColor->color_id] = $voColor;
        }
        return $data;
    }

    public function select($where = array()) {
        $sets = $this->mysqlDB()->select('tb_colors', '*', $where);
        $data = array();
        foreach ($sets as $k => $v) {
            $voColor = new VoColor();
            $voColor->convert($v);
            $data[$voColor->color_id] = $voColor;
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
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_colors', array('color_id' => $color_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }


    //

}