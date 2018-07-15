<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/16
 * Time: 18:48
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg  = '指定的商品不存在';
    public $errorCode = 20000;
}