<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;

/**
 * 管理员信息
 */
class VoOperator extends Vo {

    public $op_id;         //  操作员ID
    public $op_account;    //  登录名
    public $op_psw;        //  登录密钥     md5(str)
    public $op_name;       //  操作员名称
    public $op_auth;       //  操作员权限

}