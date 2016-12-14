<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;

/**
 * 管理员信息
 */
final class  VoOperator extends Vo {

    public $op_id;         //  操作员ID
    public $op_account;    //  登录名
    public $op_psw;        //  登录密钥     md5(str)
    public $op_name;       //  操作员名称
    public $op_phone;      //  操作员联系方式
    public $op_auth;       //  操作员权限    array
    public $status;        //  状态

    public function toArray($fields = array()) {
        $map = parent::toArray($fields);
        if (!$fields || in_array('op_auth', $fields)) {
            $map['op_auth'] = json_encode($this->op_auth);
        }
        return $map;
    }

    public function convert($data) {
        parent::convert($data);
        if (!is_array($this->op_auth)) {
            $this->op_auth = json_decode($this->op_auth, true);
        }
    }
}