<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 20:39
 */

namespace app\lib\exception;


class RarameterException extends BaseException
{
    public $code = 400;
    public $msg = '非法参数';
    public $errorCode = 999;
}