// pages/category/category.js
import { Categort } from 'category-model.js';
var categort = new Categort();
Page({

  /**
   * 页面的初始数据
   */
  data: {
      id :0,
      selected:0,
      catedata:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
     this._onadData();
  },

  _onadData:function(){
    categort.getCategoryType((catedata)=>{
      this.catedata = catedata;
        this.setData({
          categoryTypeArr: catedata.data
        });

        categort.getProductsByCategoty(catedata.data[0].id, (data) => {
          var dataObj = {
              procucts:data,
              topImgUrl: catedata.data[0].img.url,
              title: catedata.data[0].name,
          };
          this.setData({
            categoryProducts: dataObj
          })
        });
    });
  },
  categoryTaype: function (event){
    var index = categort.getDataSet(event, 'index');
    var id = categort.getDataSet(event, 'id');
    this.setData({
      selected:index
    });
    categort.getProductsByCategoty(id, (data) => {
      var dataObj = {
        procucts: data,
        topImgUrl: this.catedata.data[index].img.url,
        title: this.catedata.data[index].name,
      };
      this.setData({
        categoryProducts: dataObj
      })
    });
  },
  noProductsItemTap: function (event) {
    var id = categort.getDataSet(event, 'id');
    wx.navigateTo({
      url: '../product/product?id=' + id,
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },
})