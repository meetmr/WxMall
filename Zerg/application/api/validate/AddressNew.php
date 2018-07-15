<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 17:21
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name'          =>  'require|isNotEmpty',
        'mobile'        =>  'require',
        'province'      =>  'require|isNotEmpty',
        'city'          =>  'require|isNotEmpty',
        'country'       =>  'require|isNotEmpty',
        'detail'        =>  'require|isNotEmpty',
    ];
}