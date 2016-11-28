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

    public static $PUBLIC = array(
        'public'
    );

    /**
     * Register service
     */
    public static $JXC_SERVICE = array(
        'product' => 'Jxc\Impl\Service\ProductService',
        'custom' => 'Jxc\Impl\Service\CustomService',
        'order' => 'Jxc\Impl\Service\OrderService',
        'color' => 'Jxc\Impl\Service\ColorService',
        'public' => 'Jxc\Impl\Service\PublicService',
    );


    public static $SIDEBAR = array(
        'jxc_info_product' => 'Views/JxcInfoProduct.php',
        'jxc_info_pdt_deleted' => 'Views/JxcInfoDeleted.php',
        'jxc_info_custom' => 'Views/JxcInfoCustomer.php',
        'jxc_info_color' => 'Views/JxcInfoColors.php',
        'jxc_procure' => 'Views/JxcOrderProcure.php',
        'jxc_store_show' => '',
        'jxc_sales' => 'Views/JxcOrderSales.php',
        //  日志
        'jxc_log_procure' => 'Views/JxcLogProcure.php',
        'jxc_log_sales' => 'Views/JxcLogSales.php',


    );
}

