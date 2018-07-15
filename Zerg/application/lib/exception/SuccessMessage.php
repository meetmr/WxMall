<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 20:29
 */

namespace app\lib\exception;

class SuccessMessage extends BaseException
{
    public $code = 201;
    public $message = 'ok';
    public $errorCode = 0;
}