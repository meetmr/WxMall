<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 11:37
 */

namespace app\api\service;

use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
class Token
{
    public static function geranteToken(){
        $rendChars = getRandChar(32);
        $timestamp = time();
        $salt = config('secure.token_salt');
        return md5($rendChars.$timestamp.$salt);
    }
    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!($vars)){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('获取的Tokne变量不存在');
            }
        }
    }
    public static function getCurrentUId(){
        //1、要获取令牌
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    //权限控制 用户和cms
    public static function needPrimaryScope(){
        $scopen = self::getCurrentTokenVar('scope');
        if($scopen){
            if($scopen >= ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
    //只有用户才能访问
    public static function newCheckExclusivecope(){
        $scopen = self::getCurrentTokenVar('scope');
        if($scopen){
            if($scopen == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    public static function isValidOperate($chechedUid){
        if(!$chechedUid){
            throw new Exception('参数错误');
        }
        $uid = self::getCurrentUId();
        if($chechedUid == $uid){
            return true;
        }else{
            return false;
        }
    }
    public static function verifyToken($token){
        $exist = Cache::get($token);
        if($exist){
            return true;
        }else{
            return false;
        }
    }
}