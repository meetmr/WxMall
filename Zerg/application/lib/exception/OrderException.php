<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/20
 * Time: 14:05
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在、请检查ID';
    public $errorCode = 80000;
}