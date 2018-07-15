<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/14
 * Time: 22:36
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 400;

    public $msg = '请求的Banner不存在';

    public $errorCode = 10000;


}