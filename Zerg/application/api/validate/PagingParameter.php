<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/23
 * Time: 10:18
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page'    =>  'isPositiveInteger',
        'size'    =>  'isPositiveInteger'
    ];
    protected $message = [
        'page'  =>  '参数必须是整数哦',
        'size'  =>  '参数必须是整数哦',
    ];
}