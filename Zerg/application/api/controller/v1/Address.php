<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 17:19
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\lib\exception\UserException;
use think\facade\Request;
class Address extends BaseController
{
    protected $beforeActionList  = [
        'checkPrimaryScope' =>  ['except'=>'createUpdateAddress'],
    ];

//f6fc2f65c7a38e7336bd787708427956
    public function getUserAddress(){
        $uid = TokenService::getCurrentUId();
        $userAddress = UserAddress::where(['user_id'=>$uid])->find();
        if(!$userAddress){
            throw new UserException([
                'msg'   =>  '用户地址不存在',
                'errorCode' =>  60001,
            ]);
        }
        return json($userAddress);
    }
    public function createUpdateAddress(){
        $vallidate = new AddressNew();
        $vallidate->goCheck();
        //1、根据Token获取用户uid
        //2、根据uid判断用户是否存在。如果不存在抛出异常
        //3、获取用户从客户端提交来的地址信息
        //4、判断用户地址是否存在。来决定是否新增或者更新
        $uid = TokenService::getCurrentUId();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        $dateArray = $vallidate->getDateByRule(Request::post());
        $userAddress = $user->address;
        if($userAddress){
            $user->address()->save($dateArray);
        }else{
            $user->address->save($dateArray);
        }
       return json([
           'msg'    => 'ok'
       ]);
    }

}