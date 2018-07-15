<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/20
 * Time: 10:27
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;
use think\Db;
class   Order
{
    //订单的商品列表 客户端传来的数据
    protected $oProducts;
    //数据库查询的的数据 真实的数据
    protected $Products;
    protected $uid;
    public function place($uid,$oProducts){
        $this->oProducts =  $oProducts;
        $this->Products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return json($status);
        }
        //1、创建订单
        $orderSnap = $this->sanpOrder($status);
        $order = $this->creatrOrder($orderSnap);
        $order['pStatusArray'] = $this->Products;
        $order['pass'] = true;
        return $order;
    }
    //创建订单
    private function creatrOrder($snap){
        Db::startTrans();  //开启一个事务
        try{
            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $data = [
                'user_id'     => $this->uid,
                'order_no'    => $orderNo,
                'total_price' => $snap['orderPrice'],
                'total_count' => $snap['totalCount'],
                'snap_img'   => $snap['snapImg'],
                'snap_name'  => $snap['snapName'],
                'snap_address'=> $snap['snapAddress'],
                'snap_items' => json_encode($snap['pStatus']),
            ];
            $order->save($data);
            $orderId = $order->id;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderId;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit(); //提交事务
            $creare_time = time();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $creare_time,
            ];
        }
        catch(Exception $ex){
            Db::rollback();  //回滚事务
            throw $ex;
        }
    }
    //生成订单号
    public static function makeOrderNo(){
        $yCode = ['A','B','C','D','E','F','G','H','I','J','P','Q'];
        $ordersn = $yCode[intval(date('Y')) - 2018] .strtoupper(dechex(date('M'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $ordersn;
    }
    //生成订单快照
    private function sanpOrder($status){
        $snap = [
            'orderPrice'    => 0,
            'totalCount'    => 0,
            'pStatus'       => [],
            'snapAddress'   => '',
            'snapName'      => '',
            'snapImg'       => '',
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->Products[0]['name'];
        $snap['snapImg'] = $this->Products[0]['main_img_url'];
        if(count($this->Products) > 1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }
    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg'   =>  '用户收获地址不存在',
                'errorCode' =>  60001,
            ]);
        }
        return $userAddress->toArray();
    }
    public function checkOrderStock($orderId){
        $oProducts = OrderProduct::where(['order_id'=>$orderId])->select();
        $this->oProducts = $oProducts;
        $this->Products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }
    private function getOrderStatus(){
        $status = [
            'pass'          => true,
            'orderPrice'    => 0,
            'totalCount'    => 0,
            'pStatusArray'  => []
        ];
        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->Products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
                $status['msg'] = '商品数量不足';
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }
    private function getProductStatus($opID,$Ocount,$product){
        $pIndex = -1;
        $pStatus = [
            'id'          =>'',
            'haveStock'   =>false,
            'counts'      =>0,
            'price'       =>0,
            'name'        =>'',
            'main_img_ur'   =>'',
            'totalPrice'  =>0,
        ];
        for ($i=0; $i<count($product); $i++){
            if($opID == $product[$i]['id']){
                $pIndex = $i;
            }
        }
        if($pIndex == -1){
            //如果商品不存在
            throw new OrderException([
               'msg'    =>  $opID.'商品不存在',
            ]);
        }else{
            $product = $product[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['counts'] = $Ocount;
            $pStatus['price'] = $product['price'];
            $pStatus['main_img_url'] = $product['main_img_url'];
            $pStatus['name'] = $product['name'];
            $pStatus['totalPrice'] = $product['price'] * $Ocount;
            if($product['stock'] - $Ocount >= 0){
                $pStatus['haveStock']  = true;
            }
        }
        return $pStatus;
    }
    private function getProductsByOrder($oProducts){
        $oPIDs = [];
        foreach ($oProducts as $item){
            array_push($oPIDs,$item['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id', 'price', 'stock', 'name','main_img_url'])
            ->toArray();
        return $products;
    }
}