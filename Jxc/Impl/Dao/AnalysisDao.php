<?php
/**
 * User: TinyZ
 * Date: 2016-12-01 11:11:50
 */

namespace Jxc\Impl\Dao;


use Jxc\Impl\Core\JxcConfig;
use Jxc\Impl\Core\JxcConst;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogOrder;
use Jxc\Impl\Vo\LogOrderDetail;

final class AnalysisDao extends MySQLDao {

    private $productDao;
    private $logOrderDao;

    public function __construct($config) {
        parent::__construct($config);
        $this->productDao = new ProductDao(JxcConfig::$DB_Config);
        $this->logOrderDao = new LogOrderDao(JxcConfig::$DB_Config);
    }

    /**
     * 统计销售利润
     * <pre>
     * #    订单的毛利润:
     *   SELECT order_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id GROUP BY order_id;
     * #    统计每个用户的利润总和:
     * SELECT A.ct_id, SUM(DP.profit) AS all_profit FROM `log_order` AS A, (
     * SELECT order_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id GROUP BY order_id
     * ) AS DP WHERE A.order_id=DP.order_id AND A.`type`=1 AND A.datetime BETWEEN '' AND '' GROUP BY A.ct_id
     * @param $timeStart
     * @param $timeEnd
     */
    public function selectAnalysisProfit($timeStart, $timeEnd) {
        $type = JxcConst::IO_TYPE_SALES;
        $query = "SELECT A.ct_id, SUM(DP.profit) AS all_profit FROM `log_order` AS A, (
        SELECT order_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id GROUP BY order_id) AS DP 
        WHERE A.order_id=DP.order_id AND A.`type`={$type} AND A.datetime BETWEEN '{$timeStart}' AND '{$timeEnd}' GROUP BY A.ct_id";


    }

    /**
     * [分析]单个客户购买的尺码信息
     */
    public function analysisSingleCtmPdtSize() {
        $type = JxcConst::IO_TYPE_SALES;
        $timeStart = 1;
        $timeEnd = 1;
        $ct_id = 1;

        $query = "SELECT * FROM `log_order_detail` WHERE order_id IN (
                  SELECT order_id FROM log_order WHERE `type`={$type} AND ct_id={$ct_id} AND `datetime` BETWEEN '{$timeStart}' AND '{$timeEnd}';
                  );";
        $sets = $this->mysqlDB()->ExecuteSQL($query);
        $map = array();
        foreach ($sets as $data) {
            $logOrderDetail = new LogOrderDetail();
            $logOrderDetail->convert($data);
            $counts =  array();
            if (isset($map[$logOrderDetail->pdt_id])) {
                $counts = $map[$logOrderDetail->pdt_id];
                $map[$logOrderDetail->pdt_id] = $counts;
            }
            foreach ($logOrderDetail->pdt_counts as $k => $v) {
                if (isset($counts[$k])) {
                    $counts[$k] += $v;
                } else {
                    $counts[$k] = $v;
                }
            }
        }



    }

    /**
     * [分析]单个客户的进货数量
     * <pre>
     * SELECT pdt_id, SUM(pdt_total) AS total FROM `log_order_detail` WHERE order_id IN (
     * SELECT order_id FROM log_order WHERE ct_id=1 AND `datetime` BETWEEN '' AND ''
     * ) GROUP BY pdt_id
     * </pre>
     */
    public function analysisSingleCtmPdtPurchase() {
        $type = 1;
        $timeStart = 1;
        $timeEnd = 1;
        $ct_id = 1;
        $query = "SELECT pdt_id, SUM(pdt_total) AS total FROM `log_order_detail` WHERE order_id IN (
                  SELECT order_id FROM log_order WHERE `type`={$type} AND ct_id={$ct_id} AND `datetime` BETWEEN '{$timeStart}' AND '{$timeEnd}'
                  ) GROUP BY pdt_id;";


    }

    /**
     * [分析]单个用户的产品利润
     * <pre>
     * SELECT D.pdt_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id AND D.order_id IN (
     * SELECT order_id FROM log_order WHERE ct_id=1
     * ) GROUP BY D.pdt_id
     * </pre>
     */
    public function analysisSglCtmPdtProfit() {
        $type = 1;
        $timeStart = 1;
        $timeEnd = 1;
        $ct_id = 1;
        $query = "SELECT D.pdt_id,SUM((D.pdt_price-P.pdt_cost)*D.pdt_total) AS profit FROM log_order_detail AS D,tb_product AS P WHERE P.pdt_id=D.pdt_id AND D.order_id IN (
                  SELECT order_id FROM log_order WHERE `type`={$type} AND ct_id={$ct_id} AND `datetime` BETWEEN '{$timeStart}' AND '{$timeEnd}'
                  ) GROUP BY D.pdt_id;";


    }


}