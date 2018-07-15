<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/17
 * Time: 13:31
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    public function getAllCategories(){
        $categories = CategoryModel::all([],'img');
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return json($categories);
    }
}