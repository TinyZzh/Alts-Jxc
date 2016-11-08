<?php

namespace Jxc\Impl\Core;

/**
 * Service Interface.
 * Class JxcService
 * @package Jxc\Impl\Core
 */
class JxcService {


    public static $PUBLIC_FUNC = array();

    public function __construct() {
        $this->register();
    }

    private function register() {
        //  register method
        $methods = get_class_methods(get_class($this));
        foreach ($methods as $k => $v) {
            $PUBLIC_FUNC[$k] = $v;
        }
    }
}