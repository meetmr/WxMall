// pages/theme/theme.js
import { Theme } from "/theme-model.js";
var theme = new Theme;
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  //跳转
  noProductsItemTap: function (event) {
    var id = theme.getDataSet(event, 'id');
    wx.navigateTo({
      url: '../product/product?id=' + id,
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
      var id = options.id;
      var name = options.name;
      this.data.id = id;
      this.data.name = name;
      this._loadDate();
  },
  onReady:function(){
    wx.setNavigationBarTitle({
      title: this.data.name,
    })
  },
  _loadDate:function(){
    theme.getProductsData(this.data.id,(data)=>{
      console.log(data);
        this.setData({
            themeInfo:data.data
        });
    });
  }
})