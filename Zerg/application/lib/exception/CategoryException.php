<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/17
 * Time: 13:39
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg  = '指定的类目不存在';
    public $errorCode = 50000;
}