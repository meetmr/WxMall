<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/19
 * Time: 22:53
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use think\Controller;
class BaseController extends Controller
{
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }
    protected function checkExclusiveScope(){
        TokenService::newCheckExclusivecope();
    }
}