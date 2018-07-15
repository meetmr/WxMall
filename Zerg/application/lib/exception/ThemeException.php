<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/16
 * Time: 17:35
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;

    public $msg = '指定主题不存在';

    public $errorCode = 3000;

}