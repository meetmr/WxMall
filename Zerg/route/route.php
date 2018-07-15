<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
//获取首页轮播
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');
//获取专栏
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
//获取专栏下面的商品
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

//获取最新的商品
//Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//获取商品详细信息
//Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
//获取分类ID获取商品
//Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');

Route::group('api/:version/product',function (){
    Route::get('/recent','api/:version.Product/getRecent');
    Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
    Route::get('/by_category','api/:version.Product/getAllInCategory');
});

//获取分类
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
//获取令牌
Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

//地址管理
Route::post('api/:version/address','api/:version.Address/createUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getUserAddress');

//订单支付
Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);

//预下单
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');
//回调
Route::post('api/:version/pay/rnotify','api/:version.Pay/receiveNotify');

