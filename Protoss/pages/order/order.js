// pages/order/order.js
import { Cart } from '../cart/cart-mode.js';
// import { Order } from 'order-model.js'
import { Address } from '../utils/address.js';
import { Order } from 'order-model.js';
var address = new Address();
var cart = new Cart();
var order = new Order();
Page({

  /**
   * 页面的初始数据
   */
  data: {
      id:null,
      productsArr:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var from = options.from;
    if(from == 'cart'){
      var account = options.account;
      this._formCart(account);
    }else{
      this._fromOrder(options.id);
    }
   
  },
  _formCart:function(account){
    var productsArr;
    this.data.account = account;
    productsArr = cart.getCartDateFromLocal(true);
    this.setData({
      productsArr: productsArr,
      account: account,
      orderStatus: 0
    });
    address.getAddress((res) => {
      console.log(res.data);
      this._bindAddressInfo(res.data);
    })
  },
  editAddress:function(event){
    var that =this;
      wx.chooseAddress({  //原生收货地址
        success:function(res){
            var addressInfo = {
              name:res.userName,
              mobile:res.telNumber,
              totalDatail: address.setAddressInfo(res),
            };
         
            that._bindAddressInfo(addressInfo);
            address.submitAddress(res,(flag)=>{
            
                if(!flag){
                  that.showTips('操作提示','地址更新失败');
                }
            })
        }
      })
  },
  //下单和付款
  pay:function(){
    if (!this.data.addressInfo){
      this.showTips('下单提示','请填写收获地址');
      return;
    }
    if (this.data.orderStatus == 0){
      this._firtTimePay();
    }else{
      this._oneMoresTimePay();
    }
  },
  //第一次支付
  _firtTimePay:function(){
      var orderInfo = [];
      var procutInfo = this.data.productsArr;
      console.log(this.data.productsArr);
      for(let i = 0;i < procutInfo.length; i++){
        orderInfo.push({
          product_id:procutInfo[i].id,
          count: procutInfo[i].counts
        });
      }

      var that = this; 
      order.doOrder(orderInfo,(data)=>{
        if (data.data.pass){
            var id = data.data.order_id;
            that.data.id = id;
            // that.data.fromCartFlag = false;
            //开始支付
            that._execPay(id);
          }else{
            that._orderFali(data);
          }
      });
  },
  /*
     *下单失败
     * params:
     * data - {obj} 订单结果信息
     * */
  _orderFali: function (data) {
    var nameArr = [],
      name = '',
      str = '',
      pArr = data.data.pStatusArray;
   
    for (let i = 0; i < pArr.length; i++) {
      if (!pArr[i].haveStock) {
        name = pArr[i].name;
        if (name.length > 15) {
          name = name.substr(0, 12) + '...';
        }
        nameArr.push(name);
        if (nameArr.length >= 2) {
          break;
        }  
      }
    }
    str += nameArr.join('、');
    if (nameArr.length > 2) {
      str += ' 等';
    }
    str += ' 缺货';
    wx.showModal({
      title: '下单失败',
      content: str,
      showCancel: false,
      success: function (res) {

      }
    });
  },

  /*
      *开始支付
      * params:
      * id - {int}订单id
      */
  _execPay: function (id) {
    if (!order.onPay) {
      // this.showTips('支付提示', '本产品仅用于演示，支付系统已屏蔽', true);//屏蔽支付，提示
      this.deleteProducts(); //将已经下单的商品从购物车删除
      wx.navigateTo({
        url: '../pay-result/pay-result?id=' + id + '&flag=' + 2 + '&from=order'
      });
      return;
    }
    var that = this;
    order.execPay(id, (statusCode) => {
      if (statusCode != 0) {
        that.deleteProducts(); //将已经下单的商品从购物车删除   当状态为0时，表示
        var flag = statusCode == 2;
        wx.navigateTo({
          url: '../pay-result/pay-result?id=' + id + '&flag=' + flag + '&from=order'
        });
      }
    });
  }, //将已经下单的商品从购物车删除
  deleteProducts: function () {
    var ids = [], arr = this.data.productsArr;
    for (let i = 0; i < arr.length; i++) {
      ids.push(arr[i].id);
    }
    cart.delete(ids);
  },

  showTips:function(title,content,flag){
    wx.showModal({
      title: title,
      content: content,
      showCancel:false,
      success:function(res){
        if(!flag){
          wx.switchTab({
            url: '/pages/my/my',
          });
        }
      }
    })
  },
  _bindAddressInfo:function(addressInfo){
    this.setData({
      addressInfo: addressInfo
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  _fromOrder:function(id){
    if (id) {
      var that = this;
      // var id = this.data.id;
      order.getOrderInfoById(id, (data) => {
        that.setData({
          orderStatus: data.data.status,
          productsArr: data.data.snap_items,
          account: data.data.total_price,
          basicInfo: {
            orderTime: data.data.create_time,
            orderNo: data.data.order_no
          },
        });

        // 快照地址
        console.log(data.data.snap_address)

        addressInfo.totalDetail = address.setAddressInfo(addressInfo);
        console.log(addressInfo)
        that._bindAddressInfo(addressInfo);
      });
    }
  },
  onShow: function () {
    if(this.data.id){
      this._fromOrder(this.data.id);
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})