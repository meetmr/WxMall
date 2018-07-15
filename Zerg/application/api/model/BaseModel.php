<?php
/**
 * 模型基类
 * User: admin
 * Date: 2018/6/16
 * Time: 13:04
 */

namespace app\api\model;

use think\Model;
class BaseModel extends Model
{
    //读取器
    protected function prefixImgUrl($value,$data){
        $finalUrl = $value;
        if($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
    protected $autoWriteTimestamp = true;

}