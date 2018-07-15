<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/18
 * Time: 16:30
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address(){
        return $this->hasMany('UserAddress','user_id','id');
    }

    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}