<?php
namespace Jxc\Impl\Core;

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
        'custom' => 'Jxc\Impl\Service\CustomService',
    );


    public static $SIDEBAR = array(
        'jxc_info_product' => 'Views/JxcProductInfo.php',
        'jxc_procure' => 'Views/JxcProcure.php',
        'jxc_store_show' => '',
        'jxc_sales' => 'Views/JxcSalesOrder.php',
        'jxc_info_custom' => 'Views/JxcCustomer.php',
        'jxc_info_color' => 'Views/JxcColors.php',

    );
}

