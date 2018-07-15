<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/20
 * Time: 10:08
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{

    protected $rule = [
      'products'    => 'checkProducts',
    ];
    protected $singleRule = [
        'product_id'  =>  'require|isPositiveInteger',
        'count'       =>  'require|isPositiveInteger'
    ];
    protected function checkProducts($values){
        if(is_array($values)){
            throw new ParameterException([
                'msg'    =>  '参数错误',
            ]);
        }
        if(empty($values)){
            throw new ParameterException([
               'msg'    =>  '商品列表不能为空',
            ]);
        }
        foreach ($values as $key => $value){
            $this->checkProduct($value);
        }
        return true;
    }
    protected function checkProduct($values){
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($values);
        if(!$result){
            throw new ParameterException([
                'msg'    =>  '参数错误',
            ]);
        }
    }
}