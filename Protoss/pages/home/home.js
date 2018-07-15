// pages/home/home.js


import{HOME} from 'home-model.js';  //导入类
var home = new HOME();   //实例化类
Page({

  /**
   * 页面的初始数据
   */

  data: {
    
  },
  //页面加载
  onLoad:function(){
    this._loadDate();
  },
  //定义函数
  _loadDate:function(){
    var id = 1;
     home.getBannerDate(id,(res)=>{
      //数据绑定
      this.setData({
        'banerArray': res.data.items
      });
    });
     home.getThemeDate((res)=>{
       this.setData({
         'themeArray': res.data
       });
     });
     home.getRecent((res)=>{
       this.setData({
         'productsArr': res.data
       });
     });
  },

  
  //跳转
  noProductsItemTap:function(event){
    var id = home.getDataSet(event,'id');
    wx.navigateTo({
      url: '../product/product?id=' + id,
    })
  },

  noThemesItemTap:function(event){
    var id = home.getDataSet(event, 'id');
    var name = home.getDataSet(event,'name');
    wx.navigateTo({
      url: '../theme/theme?id=' + id +'&name=' + name,
    })
  },

})