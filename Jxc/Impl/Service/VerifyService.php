<?php

namespace Jxc\Impl\Service;

use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcConst;
use Jxc\Impl\Core\JxcService;
use Jxc\Impl\Dao\CustomerDao;
use Jxc\Impl\Dao\LogOrderDao;
use Jxc\Impl\Dao\LogOrderDetailDao;
use Jxc\Impl\Dao\OperatorDao;
use Jxc\Impl\Dao\ProductDao;
use Jxc\Impl\Libs\DateUtil;
use Jxc\Impl\Libs\W2UI;
use Jxc\Impl\Util\GameUtil;
use Jxc\Impl\Vo\LogOrder;
use Jxc\Impl\Vo\LogOrderDetail;
use Jxc\Impl\Vo\VoCustomer;
use Jxc\Impl\Vo\VoOperator;
use Jxc\Impl\Vo\VoProduct;
use Jxc\Impl\Vo\W2PdtInfo;

/**
 * 审核相关服务
 */
final class VerifyService extends JxcService {

    private $productDao;
    private $customerDao;
    private $logOrderDao;
    private $logOrderDetailDao;

    public function __construct() {
        parent::__construct();
        $this->productDao = new ProductDao(JxcConfig::$DB_Config);
        $this->customerDao = new CustomerDao(JxcConfig::$DB_Config);
        $this->logOrderDao = new LogOrderDao(JxcConfig::$DB_Config);
        $this->logOrderDetailDao = new LogOrderDetailDao(JxcConfig::$DB_Config);
    }






}