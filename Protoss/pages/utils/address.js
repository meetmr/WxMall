import { Base } from './Base.js';
import { Config } from './Config.js';
class Address  extends Base{
  constructor(){
    super();
  }
  /*是否为直辖市*/
  isCenterCity(name) {
    var centerCitys = ['北京市', '天津市', '上海市', '重庆市'],
      flag = centerCitys.indexOf(name) >= 0;
    return flag;
  }

  setAddressInfo(res) {
    var province = res.provinceName || res.province,
      city = res.cityName || res.city,
      country = res.countyName || res.country,
      detail = res.detailInfo || res.detail;

    var totalDetail = city + country + detail;
    //直辖市，取出省部分
    if (!this.isCenterCity(province)) {
      totalDetail = province + totalDetail;
    };
    return totalDetail;
  }
  _setUpAddress(res){
    var formData = {
      name: res.userName,
      province: res.provinceName, 
      city: res.cityName,
      country: res.countyName,
      mobile: res.telNumber,
      detail: res.detailInfo
    };
    return formData;
  }
  //保存用户地址
  submitAddress(data,callback){
    data = this._setUpAddress(data);
    var param = {
      url:'address',
      type:'post',
      data:data,
      sCallback:function(res){
        callback && callback(true,res);
      },eCallback(res){
        callback && callback(false, res);
      }
    }
    this.request(param);
  }

  getAddress(callback) {
    var that = this;
    var param = {
      url: 'address',
      sCallback: function (res) {
        if (res) {
          res.data.totalDatail = that.setAddressInfo(res.data);
          callback && callback(res);
        }
      }
    };
    this.request(param);
  }

}
export { Address }