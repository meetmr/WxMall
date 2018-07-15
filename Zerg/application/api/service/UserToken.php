<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/18
 * Time: 16:31
 */

namespace app\api\service;

//中间层
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;
class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    public function __construct($code){
        $this->code = $code;
        $this->wxAppID = config('wx.appid');
        $this->wxAppSecret = config('wx.secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get($code){
      $result = curl_get($this->wxLoginUrl);
      $wxResult = json_decode($result,true);
      if(empty($wxResult)){
          throw new Exception('获取失败,微信内部错误');
      }else{
          $loginFail = array_key_exists('errcode',$wxResult);
          if($loginFail){
                $this->processLoginError($wxResult);
          }else{
               return $this->gerantToken($wxResult);
          }
      }

    }
    //出来错误
    private function processLoginError($wxResult){
        throw new WeChatException([
           'msg'    =>  $wxResult['errmsg'],
           'errorCode'  => $wxResult['errcode']
        ]);
    }
    //生成令牌
    private function gerantToken($wxResult){
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $achedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($achedValue);
        return $token;
    }
    private function saveToCache($achedValue){
        $key =  self::geranteToken();
        $value = json_encode($achedValue);
        $expre_in = config('setting.token_expiren_in');
        $request = cache($key,$value,$expre_in);
        if(!$request){
            throw new TokenException([
               'msg'=> '服务器缓存异常',
                'errorCode' => 10005,
            ]);
        }
        return  $key;
    }
    private function prepareCachedValue($wxResult,$uid){
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }
    private function newUser($openid){
        $user = UserModel::create([
           'openid'     =>  $openid,
        ]);
        return $user->id;
    }
}