<?php
namespace Jxc\Impl\Core;

/**
 * Jxc常数
 * Class JxcConfig
 * @package Jxc
 */
final class JxcConst {

    //  进出类型
    const IO_TYPE_PROCURE = 1;  //  采购
    const IO_TYPE_SALES = 2;    //  销售
    const IO_TYPE_REFUND = 3;   //  退货

    //  权限管理信息
    const SYSTEM_AUTHORITY_ALL = 'all_allow';   //  最高授权

    //  状态
    const STATUS_NORMAL = 0;    //  正常
    const STATUS_DESTROY = 1;   //  废弃

    //  订单销售流程状态
    const ORDER_SALES_WAIT_WH_CF_COUNT = 1;      //  等待仓库确认库存(待配货)    ->  2,3
    const ORDER_SALES_WAIT_S_CF_PRICE = 2;       //  等待销售二次确认价格(待确认)  ->  5
    const ORDER_SALES_WAIT_S_CF_CANCEL = 3;      //  等待销售二次确认取消(待取消)  ->  4
    const ORDER_SALES_WAIT_WH_ROLLBACK = 4;      //  等待仓库完成取消的订单(回滚入库) ->  7
    const ORDER_SALES_WAIT_WH_TRANSLATION = 5;   //  等待仓库发货的订单(待出库)(配货，发货，录入物流信息)   -> 6
    const ORDER_SALES_WAIT_CUSTOM_CONFIRM = 6;   //  等待客户确认(待收货)  ->  8
    const ORDER_SALES_CANCELED = 7;              //  已取消
    const ORDER_SALES_COMPLETED = 8;             //  已完成
    //  订单采购流程状态
    const ORDER_PROCURE_WAIT_WH_CONFIRM = 1;     //  等待仓库确认(待入库)    ->  2,3







}

