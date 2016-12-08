<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;

/**
 * 仓库信息
 */
final class VoWarehouse extends Vo {

    public $id;         //  操作员ID
    public $wh_name;    //  仓库名称
    public $status;     //  状态
    public $datetime;   //  最后操作时间
}