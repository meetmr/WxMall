<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/16
 * Time: 17:11
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
      'ids' =>  'require|checkIDs'
    ];
    protected $message = [
      'ids' =>  'ids参数必须是以逗号分割的正整数',
    ];
    protected function checkIDs($value){
        $values = explode(',',$value);
        if(empty($values)){
            return false;
        }
        foreach ($values as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }
        return true;
    }
}