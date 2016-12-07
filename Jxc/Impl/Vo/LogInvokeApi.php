<?php

namespace Jxc\Impl\Vo;


use Jxc\Impl\Core\Vo;

/**
 * 接口调用日志
 */
final class LogInvokeApi extends Vo {

    public $id;         //  唯一ID
    public $service;    //  接口名
    public $request;    //  请求参数
    public $op;         //  操作员
    public $response;   //  返回信息

}