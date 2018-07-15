import{Config} from '../utils/Config.js';
import{Token} from '../utils/token.js';
class Base{

  constructor(){
    this.baseRequestUrl = Config.restUrl;
  }

  request(params,onRefetch){
    var url = this.baseRequestUrl + params.url;
    var that = this;
    if(!params.type){
      params.type = 'GET';
    }
    wx.request({
      url: url,
      data:params.data,
      method:params.type,
      header:{
        'content-type':'application/json',
        'token':wx.getStorageSync('token'),
      },
      success:function(res){
        // if (Promise.sCallBack){
        //   params.sCallBack(res);
        // }
        var code = res.statusCode.toString();  //获取code码
        var startChar = code.charAt(0);
        params.sCallback && params.sCallback(res);
        
        if(startChar == '2'){
        }else{
          if(code == '401'){
            if (!onRefetch){
              that._refetch(params);
            }
          }
          if (onRefetch){
            params.eCallBac && params.sCallBac(res);          
          }
        }    
      },
      fail:function(err){
         console.log(res);
      }
    })
  }
  _refetch(params){
    var token = new Token();
    token.getTokenFromServer((token)=>{
      this.request(params,true);
    })
  }

  //获取元素绑定的值
  getDataSet(event,key){
    return event.currentTarget.dataset[key];
  }

}

export{Base};