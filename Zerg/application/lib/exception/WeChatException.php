<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 10:19
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = '微信服务器接口调用失败';
    public $errorCode = 999;
}