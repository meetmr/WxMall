import { Base } from '../../pages/utils/Base.js';

class Theme extends Base{
  constructor(){
    super();
  }

  //获取主题下面的商品列表
  getProductsData(id, callBack){
    var params = {
      url: 'theme/' + id,
      sCallback: function (res) {
        callBack(res);
      }
    }
    this.request(params);
  }

}
export { Theme }