import { Base } from "../../pages/utils/Base.js";

class Product extends Base{
  constructor(){  
    super();
  }

  getDatailInfo(id,callBack){
    var param = {
      url:"product/" + id,
      sCallback: function (res) {
        callBack(res);
      }
    };
    this.request(param);
  }
}
export { Product }