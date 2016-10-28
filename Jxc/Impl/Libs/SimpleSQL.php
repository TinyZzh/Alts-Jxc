<?php

namespace Jxc\Impl\Libs;
use Exception;

/**
 * @Author TinyZ  ()
 */
class SimpleSQL {

    /**
     * @param string $table
     * @param string $fields
     * @return string
     */
    public static function select($table, $fields) {
        return "SELECT $fields FROM $table ;";
    }

    public static function selectWhere($table, $fields, $where, $keyword = 'AND') {
        return "SELECT $fields FROM $table" . SimpleSQL::where($where, $keyword) . ';';
    }

    // INSERT INTO `xdxy`.`tb_unique` (`uid`, `type`, `index`, `logTime`) VALUES ('myuis', '1', '5', '2015-09-29 18:23:33');
    public static function insert($table, $data = array()) {
        $sql = "INSERT IGNORE $table ";
        $val = array();
        $fields = array();
        foreach ($data as $kField => $vValue) {
            $fields[] = "`$kField`";
            if ($vValue === null) {
                $val[] = 'NULL';
            } else {
                $val[] = "'$vValue'";
            }
        }
        $sql .= '(' . implode(',', $fields) . ") VALUES (" . implode(',', $val) . ') ;';
        echo $sql."<br/>";
        return $sql;
    }

    public static function insertMulti($table, $dataAry = array()) {
        $sql = 'INSERT IGNORE ' . $table;
        $fields = array();
        $list = array();
        $length = count($dataAry);
        if ($dataAry == null || $length < 1) {
            throw new Exception('The param $data length is 0.');
        } else if ($length == 1) {
            $val = array();
            foreach ($dataAry[0] as $kField => $vValue) {
                $fields[] = "`$kField`";
                if ($vValue === null) {
                    $val[] = 'NULL';
                } else {
                    $val[] = "'$vValue'";
                }
            }
            $list[] = '(' . implode(',', $val) . ')';
        } else {
            $val = array();
            foreach ($dataAry[0] as $kField => $vValue) {
                $fields[] = "`$kField`";
                if ($vValue === null) {
                    $val[] = 'NULL';
                } else {
                    $val[] = "'$vValue'";
                }
            }
            $list[] = '(' . implode(',', $val) . ')';
            for ($i = 1; $i < $length; $i++) {
                $val = array();
                foreach ($dataAry[$i] as $kField => $vValue) {
                    if ($vValue === null) {
                        $val[] = 'NULL';
                    } else {
                        $val[] = "'$vValue'";
                    }
                }
                $list[] = '(' . implode(',', $val) . ')';
            }
        }
        $sql .= ' (' . implode(',', $fields) . ") VALUES " . implode(',', $list) . ';';
        return $sql;
    }

    public static function update($table, $data) {
        $sql = 'UPDATE ' . $table . ' SET ';
        $val = array();
        foreach ($data as $kField => $vValue) {
            if ($vValue === null) {
                $val[] = " `$kField` = NULL";
            } else {
                $val[] = " `$kField` = '$vValue'";
            }
        }
        $sql .= implode(',', $val) . ';';
        return $sql;
    }

    public static function updateWhere($table, $data, $where, $keyword = 'AND') {
        $sql = 'UPDATE ' . $table . ' SET ';
        $val = array();
        foreach ($data as $kField => $vValue) {
            if ($vValue === null) {
                $val[] = " `$kField` = NULL";
            } else {
                $val[] = " `$kField` = '$vValue'";
            }
        }
        $sql .= implode(',', $val) . SimpleSQL::where($where, $keyword) . ';';
        return $sql;
    }

    public static function deleteWhere($table, $where, $keyword = 'AND') {
        return "DELETE FROM $table" . SimpleSQL::where($where, $keyword) . ';';
    }

    public static function deleteOrWhere($table, $where, $keyword = 'OR') {
        return "DELETE FROM $table" . SimpleSQL::where($where, $keyword) . ';';
    }

    public static function where($where, $keyword = 'AND') {
        if ($where == null) {
            return '';
        }
        $w = array();
        foreach ($where as $kWf => $vWv) {
            $w[] = "`$kWf` = '$vWv'";
        }
        return ' WHERE ' . implode(" $keyword ", $w);
    }
}