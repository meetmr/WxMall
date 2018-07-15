<?php
/**
 * 订单
 * User: admin
 * Date: 2018/6/19
 * Time: 21:52
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePostivelnt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

class Order extends BaseController
{
    protected $beforeActionList  = [
        'checkExclusiveScope' =>  ['only'=>'placeOrder'],
        'checkPrimaryScope'   =>  ['only'=>'getDetail,getSummaryByUser']
    ];

    // 用户在选择商品后，向API提交所选择商品的信息
    // 检查选择的商品的库存量
    // 有库存，则把订单数据存入数据库并且返回客户端。可以支付
    // 调用支付接口、进行支付
    // 在支付的时候再次检测库存量
    // 服务器调用微信支付接口进行支付
    // 跟据微信返回的结果
    // 成功：也需要检查库存量
    // 如果支付成功进行库存量扣除、失败返回支付失败的结果
    public function placeOrder(){

        $products = input('post.');
        $uid = TokenService::getCurrentUId();
        $order = new OrderService();
        $status = $order->place($uid,$products['products']);
        return json($status);
    }
    //历史订单简要信息
    public function getSummaryByUser($page = 1,$size = 5){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUId();
        $pageOrder = OrderModel::getSummaryByUser($page,$size,$uid);
        if($pageOrder->isEmpty()){
            return json([
              'data'            =>  'null',
              'current_page'    =>  $pageOrder->getCurrentPage(),
            ]);
        }
        $data = $pageOrder->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return json([
            'data'            =>  $data,
            'current_page'    =>  $pageOrder->getCurrentPage(),
        ]);
    }
    //订单详情信息
    public function getDetail($id){
        (new IDMustBePostivelnt())->goCheck();
        $orderDetali = OrderModel::get(intval($id));
        if(!$orderDetali){
            throw new OrderException();
        }
        return json($orderDetali->hidden(['prepay_id']));
    }
}