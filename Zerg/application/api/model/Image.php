<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/15
 * Time: 22:37
 */

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id','delete_time','update_time','from'];
    //读取器
    public function getUrlAttr($value,$data){
      return $this->prefixImgUrl($value,$data);
    }
}