<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/23
 * Time: 9:04
 */

namespace app\api\service;

include (EXTEND_PATH.'WxPay/WxPay.Api.php');
use app\api\model\Order as OrderModel;
use app\api\model\Product;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use think\Exception;
use think\facade\Log;
use think\Db;
class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg){
            if($data['result_code'] == 'SUCCESS'){
                $orderNo = $data['out_trade_no'];
                db::startTrans();
            try{
                $order = OrderModel::where(['order_no',$orderNo])->lock(true)->find();
                if($order->status == 1){
                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if($stockStatus['pass']){
                        $this->updateOrderStatus($order->id,true);  //修改订单状态
                        $this->reduceStock($stockStatus);    //修改库存量
                    }else{
                        $this->updateOrderStatus($order->id,false);  //修改订单状态
                    }
                }else{
                    throw new OrderException([
                            'msg'=>'重复支付'
                        ]);
                }
                Db::commit();
                return true;
            }catch (Exception $ex){
                Db::rollback();
                Log::error($ex);
                return false;
            }
       }else{
            return true;
        }
    }
    private function reduceStock($stockStatus){
        foreach ($stockStatus['pStatusArray'] as $singlePStatus){
            Product::where(['id'=>$singlePStatus['id']])->setDec('stock',$singlePStatus['count']);
        }
    }
    private function updateOrderStatus($orderId,$success){
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUI_OUT_OF;
        OrderModel::where(['id'=>$orderId])->update(['status'=>$status]);
    }
}