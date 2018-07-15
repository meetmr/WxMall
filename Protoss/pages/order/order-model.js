import { Base } from '../../pages/utils/Base.js';

class Order extends Base{
  constructor(){
    super();
    this._storageKeyName = 'newOrder';
  }

  doOrder(param,callback){
    var that = this;
    var allParams = {
      url:'order',
      type:"POST",
      data:{products:param},
      sCallback:function(data){
        that.execSetStorageSync(true);
        callback && callback(data);
      },
      eCallback:function(){

      },
    };
    this.request(allParams);
  }

  /*
    * 拉起微信支付
    * params:
    * norderNumber - {int} 订单id
    * return：
    * callback - {obj} 回调方法 ，返回参数 可能值 0:商品缺货等原因导致订单不能支付;  1: 支付失败或者支付取消； 2:支付成功；
    * */
  execPay(orderNumber, callback) {
    var allParams = {
      url: 'pay/pre_order',
      type: 'post',
      data: { id: orderNumber },
      sCallback: function (data) {
        var create_time = data.create_time;
        if (create_time) { //可以支付
          wx.requestPayment({
            'timeStamp': timeStamp.toString(),
            'nonceStr': data.nonceStr,
            'package': data.package,
            'signType': data.signType,
            'paySign': data.paySign,
            success: function () {
              callback && callback(2);
            },
            fail: function () {
                      callback && callback(1);
            }
          });
        } else {
          callback && callback(0);
        }
      }
    };
    this.request(allParams);
  }
  execSetStorageSync(data){
    wx.setStorageSync(this._storageKeyName, data)
  }
  getOrderInfoById(id,callback){
    var that = this;
    var allParams = {
      url:'order/'+id,
      sCallback:function(data){
        callback && callback(data);
      },
    };
    this.request(allParams);
  }

  //获取所有订单
  getOrders(pageIndex,callback){
      var allParams = {
        url:'by_user',
        data:{page:pageIndex},
        type:"GET",
        sCallback:function(data){
          callback && callback(data);
        }
      };
      this.request(allParams);
      // this.requsert(allParams);
  }

}

export { Order }