<?php
namespace Jxc\Impl\Core;

/**
 * Jxc系统配置
 * Class JxcConfig
 * @package Jxc
 */
final class JxcConfig {

    /**
     * 接口调用日志
     */
    public static $API_DEBUG_LOG = true;

    /**
     * Database Config
     */
    public static $DB_Config = array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'erp_jxc',
        'user' => 'root',
        'pwd' => 'wooduan',
    );
    public static $DB_ALiYun_Config = array(
        'host' => 'bdm254844558.my3w.com',
        'port' => 3306,
        'dbname' => 'bdm254844558_db',
        'user' => 'bdm254844558',
        'pwd' => 'godlike88115',
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
        //  信息
        'jxc_info_product' => 'Views/JxcInfoProduct.php',
        'jxc_info_custom' => 'Views/JxcInfoCustomer.php',
        'jxc_info_color' => 'Views/JxcInfoColors.php',
        'jxc_info_size' => '',
        'jxc_info_invalid_pdt' => 'Views/JxcInfoInvalidPdt.php',
        'jxc_info_invalid_ctm' => 'Views/JxcInfoInvalidCtmer.php',
        //
        'jxc_procure' => 'Views/JxcOrderProcure.php',
        'jxc_store_show' => '',
        'jxc_sales' => 'Views/JxcOrderSales.php',
        //  日志
        'jxc_log_procure' => 'Views/JxcLogProcure.php',
        'jxc_log_sales' => 'Views/JxcLogSales.php',
        'jxc_log_refund' => 'Views/JxcLogRefund.php',

        //  统计分析
        'jxc_analysis_info' => 'Analysis/A1.php',
        //  系统管理
        'sys_self_info' => 'Views/SysSelfInfo.php',


    );
}

