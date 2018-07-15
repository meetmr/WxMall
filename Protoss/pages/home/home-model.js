import { Base } from '../utils/Base.js';

class HOME extends Base{
  constructor(){
      super();
  }

  getBannerDate(id, callBack){ 
    var params = {
      url: 'banner/' + id,
      sCallback:function(res){
          callBack(res);
      }
    }
    this.request(params);
  }
  getThemeDate(callBack){
    var params = {
      url: 'theme?ids=1,2,3',
      sCallback: function (res) {
        callBack(res);
      }
    }
    this.request(params);
  }

  getRecent(callBack){
    var params = {
      url: 'product/recent?count=15',
      sCallback: function (res) {
        callBack(res);
      }
    }
    this.request(params);
  }
}

//输出类
export {HOME};