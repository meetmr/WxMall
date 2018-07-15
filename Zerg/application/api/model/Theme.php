<?php
/**
 *
 * User: 王恒兵
 * Date: 2018/6/16
 * Time: 16:54
 */

namespace app\api\model;

class Theme extends BaseModel
{
    protected $hidden = [
        'delete_time','update_time',
        'topic_img_id','head_img_id'
    ];
    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
    //一对一
    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }
    //多对多
    public function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProducts($id){
        $theme = self::with('products,topicImg,headImg')
            ->find($id);
        return $theme;
    }
}