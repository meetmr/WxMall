// pages/my/my.js
import { My } from './my-model.js';
import { Address } from '../../pages/utils/address.js';
import { Order }  from '../order/order-model.js';
var my = new My();
var address = new Address();
var order = new Order();
Page({

  /**
   * 页面的初始+数据
   */
  data: {
      userInfo:'',
      pageIndex:1,
      orderArr:[],
      isLoadAll:false,
  },  

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
      this._loadData();
      this._getAddresInfo();
  },
  _getAddresInfo:function(){
    address.getAddress((addressInfo)=>{
      this._bindAddresInfo(addressInfo)
    })
  },
  _bindAddresInfo: function (addressInfo){
      this.setData({
        addressInfo: addressInfo.data
      });
  },
  _loadData:function(){
    if (getApp().globalData.userInfo){
      this.setData({
        userInfo: getApp().globalData.userInfo,
        path:false,
      });
    }else{
      var suserInfo = {};
      suserInfo.avatarUrl = "../../imgs/icon/user@default.png",
        suserInfo.nickName = '零食商贩';
      this.setData({
        path: true,
        userInfo: suserInfo
      });
    }

    this._getOrders();
   
  },
  
  getUserInfo:function(e){
    if (e.detail.userInfo){
      var userinfo = e.detail.userInfo;
      this.data.userinfo = userinfo;
      getApp().globalData.userInfo = e.detail.userInfo;
      this.setData({
        userInfo: userinfo,
        path: false,
      });
    }else{
      var userInfo = {};
      userInfo.avatatUrl = "../../imgs/icon/user@default.png",
      userInfo. nickName='零食商贩';
      this.setData({
        userInfo: userinfo
      });
    }
   
  },
  _getOrders:function(){
    order.getOrders(this.data.pageIndex,(res)=>{
      var data = [];
       data = res.data.data.data;
       console.log(data);
        if(data){
          this.data.orderArr.push.apply(this.data.orderArr,data);
          this.setData({
            orderArr: this.data.orderArr
          });
        }else{
          this.data.isLoadAll = true;
        }
       
    });
  },
  onShow:function(){
    // this._loadData();
  },
  onReachBottom:function(){
    if (!this.data.isLoadAll){
      this.data.pageIndex++;
      this._getOrders();
    }
  },
  showOrderDetailInfo:function(event){
      var id = order.getDataSet(event,'id');
      wx.navigateTo({
        url: '../order/order?from=order&id=' + id,
      });
  }
})