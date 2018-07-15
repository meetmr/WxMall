<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/18
 * Time: 16:24
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
      'code'    =>  'require|isNotEmpty'
    ];
    protected $message = [
      'code'    =>  'code不能为空',
    ];
}