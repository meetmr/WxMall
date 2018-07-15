<?php
/**
 * User: 王恒兵
 * Date: 2018/6/14
 * Time: 20:58
 */

namespace app\api\validate;

class IDMustBePostivelnt extends BaseValidate
{
    protected $rule = [
        'id'    =>  'require|isPositiveInteger',
    ];
    protected $message = [
      'id'  =>  'id必须是整数',
    ];
}