<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/21
 * Time: 20:56
 */


namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = [
      'user_id' ,'delete_time'  ,'update_time'
    ];
    public function getSnapItemsAttr($value){
        if(empty($value)){
            return '';
        }
        return json_decode($value);
    }
    public function getSnapAddressAttr($value){
        if(empty($value)){
            return '';
        }
        return json_decode($value);
    }
    public static function getSummaryByUser($page = 1,$size = 15,$uid){
      $pagingDate = self::where(['user_id'=>$uid])
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
      return $pagingDate;
    }
}