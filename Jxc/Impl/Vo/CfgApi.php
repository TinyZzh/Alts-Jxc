<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * [配置表]身份权限
 */
final class CfgApi extends Vo {

    public $id;     //  唯一ID
    public $name;   //  名称
    public $func;   //  函数
    public $lv;     //  级别
    public $comment;   //  备注

}