<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/16
 * Time: 18:29
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePostivelnt;
use app\lib\exception\ProductException;
class Product
{
    //获取最新的商品
    public function getRecent($count = 15){
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return json($products);
    }

    //获取分类ID获取商品
    public function getAllInCategory($id){
        (new IDMustBePostivelnt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return json($products);
    }
    //获取商品详细信息
    public function getOne($id){
        (new IDMustBePostivelnt())->goCheck();
        $product = ProductModel::getProductsDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return json($product);
    }
    //删除一个商品
    public function deleteOne(){

    }
}