<?php
namespace Jxc;

/**
 * Jxc系统配置
 * Class JxcConfig
 * @package Jxc
 */
class JxcConfig {

    /**
     * Database Config
     */
    public static $DB_Config = array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'erp_jxc',
        'user' => 'root',
        'pwd' => '123456',
    );

    /**
     * Register service
     */
    public static $JXC_SERVICE = array(
        'product' => 'Jxc\Impl\Service\ProductService',
    );
}

