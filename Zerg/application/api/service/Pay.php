<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/22
 * Time: 16:33
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use think\facade\Log;
include (EXTEND_PATH.'WxPay/WxPay.Api.php');
class Pay
{
    private $orderId;
    private $orderNo;
    public function __construct($orderId){
        if(!$orderId){
            throw  new Exception('订单号不能为空');
        }
        $this->orderId = $orderId;
    }
    public function pay(){
        // 检查是否存在订单号
        // 订单号存在、但是和当前用户不匹配
        // 检查是否支付过没有
        // 检查库存量
        $this->checkOrderValid();
        $orderSevice = new Order();
        $status = $orderSevice->checkOrderStock($this->orderId);
        if(!$status['pass']){
            return $status;
        }
       return $this->makeWxPreOrder($status['orderPrice']);
    }
    private function makeWxPreOrder($totalPrice){
        //openid
        $openId = Token::getCurrentTokenVar('openid');
        if(!$openId){
            throw new TokenException();
        }
        $wxOrderDate = new \WxPayUnifiedOrder();
        $wxOrderDate->SetOut_trade_no($this->orderNo);
        $wxOrderDate->SetTrade_type('JSAPI');
        $wxOrderDate->SetTotal_fee($totalPrice * 100);
        $wxOrderDate->SetBody('零食商贩');
        $wxOrderDate->SetOpenid($openId);
        $wxOrderDate->SetNotify_url(config('secure.pay_back_url'));
        return  $this->getPaySignature($wxOrderDate);
    }
    private function getPaySignature($wxOrderDate){
        $wxorder = \WxPayApi::unifiedOrder($wxOrderDate);
        if($wxorder['return_code'] != 'SUCCESS' || $wxorder['result'] != 'SUCCESS'){
            Log::record($wxorder,'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recoderPreOder($wxorder);
        $signDate = $this->sign($wxorder);
        return $signDate;
    }
    private function sign($wxOrder){
        $jsApiPayDate = new \WxPayJsApiPay();
        $jsApiPayDate->SetAppid(config('wx.appid'));
        $jsApiPayDate->SetTimeStamp((string)time());
        $str = md5(time().mt_rand(0,1000));
        $jsApiPayDate->SetNonceStr($str);
        $jsApiPayDate->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayDate->SetSignType('md5');
        $sign = $jsApiPayDate->MakeSign();
        $rawValues = $jsApiPayDate->GetValues();
        $rawValues['sign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }
    private function recoderPreOder($wxOrder){
        OrderModel::where(['id'=>$this->orderId])->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }
    public function checkOrderValid(){
        $order = OrderModel::where('id','=',$this->orderId)->find();
        if(!$order){
            throw new OrderException();
        }
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
               'ms' =>  '订单与用户不匹配',
               'errorCode'  => 10003
            ]);
        }
        if($order->status != OrderStatusEnum::UNPAID){
               throw new OrderException([
                  'msg'         => '订单状态异常',
                  'errorCode'   => 80003,
                  'code'        => 400
               ]);
        }
        $this->orderNo = $order->order_no;
        return true;
    }

}