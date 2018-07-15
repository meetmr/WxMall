<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 15:09
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = [
        'product_id','delete_time','id'
    ];
}