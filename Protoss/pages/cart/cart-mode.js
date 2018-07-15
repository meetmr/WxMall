import { Base } from '../../pages/utils/Base.js';
class Cart extends Base{
  constructor(){
    super();
    this._storageKeyName = 'cart';
  }
  /*
    * 加入购物车
    * 如果之前没有这样子的商品，则直接添加一条新记录、数量为counts
    * 如果有，则只将相应的数量 + 1

  */
  add(item, counts){
    console.log(item);
    console.log(counts);
      var cartDate = this.getCartDateFromLocal();
      var isHasInfo = this._isHasThatOne(item.id,cartDate);
      if(isHasInfo.index == -1){
        item.counts = counts;
        item.selectStatus = true;  //设置选中状态
        cartDate.push(item);
      }else{
        cartDate[isHasInfo.index].counts += counts;
      }
      // console.log(this._storagekeyName);
      wx.setStorageSync('cart',cartDate);
  }
  /**
   * 
   * 从缓存中获取数据
   * 
  */
  getCartDateFromLocal(flag){
    var res = wx.getStorageSync('cart');
    if(!res){
      res = [];
    }
    if(flag){
      var newRow = [];
      for(let i=0; i< res.length;i++){
        if (res[i].selectStatus){
          newRow.push(res[i]);
        }
      }
      return newRow;
    }
    return res;
  }
  /**
   * 
   * flag 考虑选择状态
   * 
  */
  getCaetTotalCuounts(flag){
    var data = this.getCartDateFromLocal();    
    var counts = 0;
    for(let i = 0; i<data.length; i++){
      if(flag){
        if (data[i].selectStatus){
          counts = counts + data[i]['counts'];
        }
      }else{
         counts = counts + data[i]['counts'];
      }
    }
    return counts;
  }
  /**
   * 判断某个商品是否在购物车
   * 
  */
  _isHasThatOne(id,arr){
    var item;
    var result = {index:-1};
    for(let i = 0;i < arr.length; i++){
      item = arr[i];
      if(item.id == id){
        result = {
          index:i,
          data:item
        };
       break;
      }
    
    }
    return result;
  }

  _changeCounts(id,counts){
    var cartDate = this.getCartDateFromLocal();
    var hasInfo = this._isHasThatOne(id,cartDate);
    if(hasInfo.index != -1){
      if(hasInfo.data.counts != 1){
        cartDate[hasInfo.index].counts += counts;
      }
    }
    wx.setStorageSync('cart', cartDate);
  };

  addCounts(id){
    this._changeCounts(id,1);
  };
  cutCotuns(id){
    this._changeCounts(id,-1);
  };
  delete(ids){
    if(!(ids instanceof Array)){
      ids = [ids];
    }
    var cartData = this.getCartDateFromLocal();
    for(let i = 0;i<ids.length;i++){
      var hasIndex = this._isHasThatOne(ids[i],cartData);
      if(hasIndex.index != -1){
        cartData.splice(hasIndex.index,1);
      }
    }
    wx.setStorageSync('cart', cartData);
  }
  execSetStorageSync(data){
    wx.setStorageSync('cart', data);
  }
}

export { Cart };