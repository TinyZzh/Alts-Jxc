<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;

/**
 * 产品尺码配置
 * Class VoPdtSize
 * @package Jxc\Impl\Vo
 */
class VoPdtSize extends Vo {
    public $pdt_id;
    public $sizes;  //  3XS|2XS|XS|S|M|L|XL|2XL|3XL

    public function toArray($fields = array()) {
        $map = parent::toArray($fields);
        $map['sizes'] = implode("|", $this->sizes);
        return $map;
    }

    public function convert($data) {
        parent::convert($data);
        $this->sizes = explode("|", $this->sizes);
    }
}