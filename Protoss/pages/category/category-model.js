import { Base } from '../../pages/utils/Base.js';

class Categort extends Base{
  constructor(){
    super();
  }

//获取所有分类
  getCategoryType(callBack){
    var param = {
      url:'category/all',
      sCallback: function (data) {
        callBack(data);
      }
    };
    this.request(param);
  }
  //获取分类下面的商品
  getProductsByCategoty(id,callBack){
    var param = {
      url: 'product/by_category?id=' + id,
      sCallback: function (data) {
        callBack(data);
      }
    };
    this.request(param);
  }
}

export {Categort};