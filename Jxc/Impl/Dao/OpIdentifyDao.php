<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\VoOpIdentify;

/**
 * 权限Dao
 */
class OpIdentifyDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }
    
    
    
    

    /**
     * @param $account
     * @param int $status 账户状态
     * @return VoOpIdentify|null
     */
    public function selectByAccount($account, $status = 0) {
        $sets = $this->mysqlDB()->select('tb_op_identify', '*', array('op_account' => $account, 'status' => $status));
        if ($sets && is_array($sets)) {
            $voOpIdentify = new VoOpIdentify();
            $voOpIdentify->convert($sets[0]);
            return $voOpIdentify;
        }
        return null;
    }

    /**
     * @param $op_id
     * @param int $status 账户状态
     * @return VoOpIdentify|null
     */
    public function selectById($op_id, $status = 0) {
        $sets = $this->mysqlDB()->select('tb_op_identify', '*', array('op_id' => $op_id, 'status' => $status));
        if ($sets && is_array($sets)) {
            $voOpIdentify = new VoOpIdentify();
            $voOpIdentify->parse($sets[0]);
            return $voOpIdentify;
        }
        return null;
    }

    /**
     * @param int $status 账户状态
     * @return array
     */
    public function selectByStatus($status = 0) {
        $sets = $this->mysqlDB()->select('tb_op_identify', '*', array('status' => $status));
        $array = array();
        foreach ($sets as $k => $v) {
            $voOpIdentify = new VoOpIdentify();
            $voOpIdentify->convert($v);
            $array[$voOpIdentify->oi_id] = $voOpIdentify;
        }
        return $array;
    }

    /**
     * @param $voOpIdentify VoOpIdentify
     * @return VoOpIdentify
     * @throws Exception
     */
    public function insert($voOpIdentify) {
        $query = $this->mysqlDB()->sqlInsert('tb_op_identify', $voOpIdentify->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $voOpIdentify->oi_id = $this->mysqlDB()->getInsertId();
        return $voOpIdentify;
    }

    /**
     * @param $voOpIdentify VoOpIdentify
     * @param array $fields
     */
    public function updateByFields($voOpIdentify, $fields = array()) {
        $query = $this->mysqlDB()->sqlUpdateWhere('tb_op_identify', $voOpIdentify->toArray($fields), array('oi_id' => $voOpIdentify->oi_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }

    /**
     * @param $op_id  int  操作员唯一ID
     * @throws Exception
     */
    public function delete($op_id) {
        $query = $this->mysqlDB()->sqlDeleteWhere('tb_op_identify', array('op_id' => $op_id));
        $this->mysqlDB()->ExecuteSQL($query);
    }
}