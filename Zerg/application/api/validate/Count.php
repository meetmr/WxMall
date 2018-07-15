<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/16
 * Time: 18:37
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' =>  'isPositiveInteger|between:1,15',
    ];

}