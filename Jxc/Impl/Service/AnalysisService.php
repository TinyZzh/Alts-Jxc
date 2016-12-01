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
 * 分析
 */
final class AnalysisService extends JxcService {

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

    /**
     * 分析利润率
     *
     * <pre>
     * #  订单的毛利润:
     *   SELECT order_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id GROUP BY order_id;
     * #    统计每个用户的利润总和
     * SELECT A.ct_id, SUM(DP.profit) AS all_profit FROM `log_order` AS A, (
     * SELECT order_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id GROUP BY order_id
     * ) AS DP WHERE A.order_id=DP.order_id AND A.`type`=1 AND A.datetime BETWEEN '' AND '' GROUP BY A.ct_id
     *
     * </pre>
     *
     * @param $voOp
     * @param $request
     */
    public function analysisProfit($voOp, $request) {



    }



}