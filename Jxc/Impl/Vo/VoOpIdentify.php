<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * [配置表]身份权限
 */
final class VoOpIdentify extends Vo {

    public $oi_id;     //  唯一ID
    public $oi_name;   //  授权身份
    public $oi_auth;   //  权限信息
    public $status;    //  状态
    public $comment;   //  备注

}