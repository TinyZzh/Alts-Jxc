<?php

namespace Jxc\Impl\Vo;

use Jxc\Impl\Core\Vo;
use Jxc\Impl\Libs\W2UI;

/**
 * 产品数量信息
 */
class VoPdtCount extends Vo {

    public $pdt_counts;  //  数量 array

    public function __construct() {
        $this->pdt_counts = array();
        for ($i = 0; $i < 10; $i++) {
            $this->pdt_counts[] = '';
        }
    }

    public function toArray($fields = array()) {
        $map = parent::toArray($fields);
        if (!$fields || in_array('pdt_counts', $fields)) {
            $map['pdt_counts'] = implode("|", $this->pdt_counts);
        }
        return $map;
    }

    public function convert($data) {
        parent::convert($data);
        if (!is_array($this->pdt_counts)) {
            $this->pdt_counts = explode("|", $this->pdt_counts);
        }
    }
}