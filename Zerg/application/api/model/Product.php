<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/16
 * Time: 16:56
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = [
      'delete_time','create_time','update_time',
      'category_id','from','pivot'
    ];
    public function getMainImgUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }
    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }
    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');

    }
    public static function getMostRecent($count){
        $products = self::limit($count)->order('create_time desc')->select();
        return $products;
    }
    public static function getProductsByCategoryID($CategoryID){
        $products = self::where('category_id','=',$CategoryID)->select();
        return $products;
    }
    public static function getProductsDetail($id){
        $product = self::with(['properties'])
            ->with([
            'imgs'  =>  function($query){
                $query->with(['imgUrl'])
                    ->order('order','asc');
            }
         ])->find($id);
        return $product;
    }
}