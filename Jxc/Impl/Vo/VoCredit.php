<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;

/**
 * 信用额度管理
 */
final class VoCredit extends Vo {

    public $cl_lv;      //  信用等级
    public $cl_max;     //  透支最大额度  透支满将无法进行交易
    public $cl_warn;    //  警报阀值    低于max   透支额度超过阀值 - 显示红色

}