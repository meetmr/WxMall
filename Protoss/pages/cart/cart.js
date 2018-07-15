// pages/cart/cart.js
import { Cart } from '../../pages/cart/cart-mode.js';
var cart = new Cart;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },
  onHide:function(){
    cart.execSetStorageSync(this.data.cartList);
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var cartList = cart.getCartDateFromLocal();
    // var countsInfo = cart.getCaetTotalCuounts(true);
    var cal = this._calcTotalAccountAndCounts(cartList);
    this.setData({
        cartList: cartList,
        selectedTypeCounts: cal.selectedTypeCounts,
        sumPrice: cal.sumPrice,
        selectedCounts: cal.selectedCounts,
    })
  },
  _calcTotalAccountAndCounts:function(data){
    var sumPrice = 0;  //总价格
    var len = data.length; 
    var selectedCounts = 0;   //总个数
    var selectedTypeCounts = 0;  //类型总数
    let multiple = 100;
    for(let i =0;i < len; i++){
      if (data[i].selectStatus){
        sumPrice += data[i]['price'] * multiple * Number(data[i]['counts']) * multiple;
        selectedCounts += data[i].counts;
        selectedTypeCounts++;
      }
    }
    return {
      selectedCounts: selectedCounts,
      selectedTypeCounts: selectedTypeCounts,
      sumPrice: sumPrice / (multiple * multiple)
    }
  },
  toggleSelect:function(event){
    var id = cart.getDataSet(event,'id');
    var status = cart.getDataSet(event, 'status');
    var index = this._getProductIndexById(id);
    this.data.cartList[index].selectStatus = !status;
    this._resetCartData();
  },
  _resetCartData:function(){
    var newDate = this._calcTotalAccountAndCounts(this.data.cartList);
    this.setData({
      cartList: this.data.cartList,
      selectedTypeCounts: newDate.selectedTypeCounts,
      sumPrice: newDate.sumPrice,
      selectedCounts: newDate.selectedCounts,
    })
  },
  toggleSelectAll:function(event){
    var status = cart.getDataSet(event,'status') == 'true';
    var data = this.data.cartList;
    var len = data.length;
    for(let i = 0;i<len;i++){
      data[i].selectStatus = !status;
    }
    this._resetCartData();
  },
  _getProductIndexById:function(id){
    var data = this.data.cartList;
    var len = data.length;
    for(let i = 0;i<len;i++){
      if(data[i].id == id){
        return i;
      }
    }
  },
  changeCounts:function(event){
    var id = cart.getDataSet(event, 'id');
    var types = cart.getDataSet(event,'type');
    var index = this._getProductIndexById(id);
    var counts = 1;
    if (types == 'add'){
      cart.addCounts(id);
    }else{
      var counts = -1;
      cart.cutCotuns(id);
    }
    this.data.cartList[index].counts += counts;
    this._resetCartData();
  },
  delete:function(event){
    var id = cart.getDataSet(event, 'id');
    var index = this._getProductIndexById(id);
    this.data.cartList.splice(index,1);
    this._resetCartData();
    cart.delete(id);
  },
  submitOrder:function(event){
    wx.navigateTo({
      url: '../order/order?account=' + this.data.sumPrice +'&from=cart',
    })
  }
})