<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/14
 * Time: 22:03
 */

namespace app\api\model;

class Banner extends  BaseModel
{
    protected $hidden = ['delete_time','update_time'];
    //关联模型  一对多
    public function items(){
       return $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerById($id){
        $result = self::with(['items','items.img'])->find($id);
        return $result;
    }
}