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
class VoCustomer extends Vo {

    public $ct_id;       //  客户ID
    public $ct_name;     //  客户姓名
    public $ct_address;  //  通信地址
    public $ct_phone;    //  联系电话
    public $ct_money;    //  账户余额   -   手动充值    浮点数
    public $last_order;  //  上一个订单
    public $status;      //  客户状态   0:正常 1:废弃

}