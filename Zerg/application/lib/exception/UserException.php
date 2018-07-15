<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 18:15
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $message = '用户不存在';
    public $errorCode = 60000;
}