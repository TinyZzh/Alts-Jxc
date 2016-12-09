<?php
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2016/10/26
 * Time: 22:29
 */

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * 消费者-会员
 */
final class VoCustomer extends Vo {

    public $ct_id;          //  客户ID
    public $status;         //  客户状态   0:正常 1:废弃
    public $ct_name;        //  客户姓名
    public $ct_province;    //  省
    public $ct_city;        //  地
    public $ct_area;        //  县
    public $ct_address;     //  通信地址
    public $ct_phone;       //  联系电话
    public $ct_money;       //  账户余额   -   手动充值    浮点数
    //  信用额度
    public $credit_lv;      //  信用等级
    public $credit_line;    //  信用额度
    //  订单
    public $last_order;     //  最后一个订单
    public $time_last_order;//  最后一个订单时间

}