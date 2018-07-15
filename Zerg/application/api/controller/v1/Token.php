<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/18
 * Time: 16:23
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
class Token
{
    public function getToken($code = ''){
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token  = $ut->get($code);
        return json([
            'token' =>$token
        ]);
    }
    
    public function verifyToken($token = ''){
        if(!$token){
            throw new ParameterException([
                'token不能为空',
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return json([
            'isValid'   =>  $valid
        ]);
    }
}