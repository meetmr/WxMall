<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/14
 * Time: 22:03
 */

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id','img_id','banner_id','update_time','delete_time'];
    //关联模型  一对一
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}