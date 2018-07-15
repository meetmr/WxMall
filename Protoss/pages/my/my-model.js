import { Base } from '../../pages/utils/Base.js'

class My extends Base{
  constructor(){
    super();
  }

  getUserInfo(){
    var that = this;
    wx.login({
      success:function(){
        wx.getUserInfo({
          success:function(res){
            typeof cd == "function" && cb(res.userInfo);
          },
          fail:function(res){
            typeof cb == "function" && cb({
                avatatUrl : "../../imgs/icon/user@default.png",
                nickName:'零食商贩'
            });
          }
        })
      }
    })
  }
}

export { My }