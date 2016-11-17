<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\VoOperator;

/**
 * 操作员Dao
 * Class ProductDao
 * @package Jxc\Impl\Dao
 */
class OperatorDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * @param $account
     * @return VoOperator|null
     */
    public function selectByAccount($account) {
        $sets = $this->mysqlDB()->select('tb_operator', '*', array('account' => $account));
        if ($sets && is_array($sets)) {
            $voOperator = new VoOperator();
            $voOperator->convert($sets[0]);
            return $voOperator;
        }
        return null;
    }

    /**
     * @param $voOperator VoOperator
     * @return VoOperator
     * @throws Exception
     */
    public function insert($voOperator) {
        $query = $this->mysqlDB()->sqlInsert('tb_operator', $voOperator->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voOperator->op_id = $this->mysqlDB()->getInsertId();
        return $voOperator;
    }

    /**
     * @param $voOperator VoOperator
     * @param array $fields
     */
    public function updateByFields($voOperator, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_operator', $voOperator->toArray($fields), array('op_id' => $voOperator->op_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $op_id  int  操作员唯一ID
     * @throws Exception
     */
    public function delete($op_id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_operator', array('op_id' => $op_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }
}