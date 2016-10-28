<?php

namespace Jxc\Impl\Libs;
use Exception;
use mysqli_stmt;

/**
 * Simple PHP mysqli Wrapper
 * @author TinyZ
 * @mail tinyzzh815@gmail.com
 * @require PHP version 5.3+ above
 * @license MIT
 */
class JxcMySQLi extends MysqliDb {

    protected $_stmtStatus;

    /**
     * 执行SQL
     * @param string $query 查询语句
     * @return array|bool|int
     * @throws Exception
     */
    public function ExecuteSQL($query) {
        // Prepare query
        $stmt = $this->_prepare($query);
        $this->_stmtStatus = $stmt->execute();
        if ($stmt->error) {
//            echo 'Error => '.$query.'<br/>';
            throw new Exception($stmt->error);
        }
        $this->reset();
        $this->_stmtError = $stmt->error;
        $operation = strtoupper(substr($query, 0, 6));
        switch ($operation) {
            case 'SELECT':
                return $this->_dynamicBindResults($stmt);
            case 'INSERT':
                $this->count = $stmt->affected_rows;
                if ($stmt->affected_rows < 1)
                    return false;
                if ($stmt->insert_id > 0)
                    return $stmt->insert_id;
                return true;
            case 'UPDATE':
                $this->count = $stmt->affected_rows;
                return $this->_stmtStatus;
            case 'DELETE':
                return ($stmt->affected_rows > 0);
            default:
                return null;
        }
    }

    // ------------  Public Functions -----------------------

    public function sqlInsert($tableName, $insertData) {
        return SimpleSQL::insert($tableName, $insertData);
    }

    public function sqlSelectWhere($tableName, $columns, $where = array()) {
        return SimpleSQL::selectWhere($tableName, $columns, $where);
    }

    public function sqlUpdateWhere($tableName, $columns, $where = array()) {
        return SimpleSQL::updateWhere($tableName, $columns, $where);
    }

    public function sqlDeleteWhere($tableName, $where = array()) {
        return SimpleSQL::deleteWhere($tableName, $where);
    }

    public function select($tableName, $columns = '*', $where = array()) {
        return $this->ExecuteSQL(SimpleSQL::selectWhere($tableName, $columns, $where));
    }

    /**
     * 预处理
     * @param $query
     * @return mysqli_stmt
     */
    protected function _prepare($query) {
        if (!$stmt = $this->mysqli()->prepare($query)) {
            trigger_error("Problem preparing query ($query) " . $this->mysqli()->error, E_USER_ERROR);
            // 断线重连
//            if (!$this -> _mysqli -> ping()) {
//                echo 'tinyz';
//                $this -> __destruct();
//                $this -> connect();
//                if (!$stmt = $this->_mysqli->prepare($query)) {
//                    trigger_error("Problem preparing query ($query) " . $this->_mysqli->error, E_USER_ERROR);
//                }
//            }
        }
        if ($this->traceEnabled)
            $this->traceStartQ = microtime(true);
        return $stmt;
    }

    // -------------------  Override Functions -----------------------
//    protected function _buildQuery($numRows = null, $tableData = null) {
//        $this->_buildJoin();
//        $this->_buildTableData($tableData);
//        $this->_buildWhere();
//        $this->_buildGroupBy();
//        $this->_buildOrderBy();
//        $this->_buildLimit($numRows);
//        $sqlQuery = $this->replacePlaceHolders($this->_query, $this->_bindParams);
//        $this->_lastQuery = $sqlQuery;
//        return $sqlQuery;
//    }
}