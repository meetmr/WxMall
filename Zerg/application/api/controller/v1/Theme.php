<?php
/**
 * 专栏控制器
 * User: 王恒兵
 * Date: 2018/6/16
 * Time: 16:54
 */

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostivelnt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * @url /theme?ids=id1,id2,id2....
     */
    public function getSimpleList($ids = ''){
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
        if($result->isEmpty()){
            throw new ThemeException();
        }
        return $result;
    }
    /**
     * @url /theme/:id
     */
    public function getComplexOne($id){
        (new IDMustBePostivelnt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if(!$theme){
            throw new ThemeException();
        }
        return json($theme);
    }
}