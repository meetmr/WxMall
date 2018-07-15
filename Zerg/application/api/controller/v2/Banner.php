<?php
namespace app\api\controller\v2;

use app\api\validate\IDMustBePostivelnt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     *  获取指定id的banner的信息
     * @url /banner/id
     * @http GET
     * @id banner的id号
     * */
    public function getBanner($id)
    {
        (new IDMustBePostivelnt())->goCheck();
        $banner = BannerModel::getBannerById(intval($id));
        if(!$banner){
            throw new BannerMissException;
        }
        return json($banner);
    }
}
