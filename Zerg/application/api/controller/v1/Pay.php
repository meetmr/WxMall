<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/22
 * Time: 16:08
 */

namespace app\api\controller\v1;


use app\api\service\WxNotify;
use app\api\validate\IDMustBePostivelnt;
use app\api\service\Pay as PayService;
class Pay extends BaseController
{
    protected $beforeActionList  = [
        'checkExclusiveScope' =>  ['only'=>'getPreOrder'],
    ];
    //预订单信息
    public function getPreOrder($id = ''){
        (new IDMustBePostivelnt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }
    //支付回调
    public function receiveNotify(){
        //1、检查库存量
        //2、更新订单的状态
        //3、减库存
        //如果成功处理、返回成功、如果失败、就继续发送
        $ontify = new WxNotify();
        $ontify->Handle();
    }
}