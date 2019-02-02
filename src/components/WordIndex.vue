<template>
  <div class="index-container">
    <h1><strong>胖番茄网</strong> -- <em>英语四级单词训练</em></h1>
    <div class="tab-header">
      <tab :line-width=2 active-color='#52A3E3' v-model="tabIndex">
        <tab-item class="vux-center" :selected="index === tabIndex"  v-for="(value,index) in tabList"  :key="index" >
          <div class="box-center color2">
            <p>{{value}}</p>
          </div>
        </tab-item>
      </tab>
    </div>
    <div class="index-main">
      <swiper v-model="tabIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper">
        <swiper-item v-for="(swiperItem,swiperIndex) in tabList" class="box-justify item"  :key="swiperIndex" :selected="swiperIndex===tabIndex"> 
          <div class="item-wrapper" v-if="swiperIndex < 5">
            <div v-for="(item,index) in list" class="box-justify item"> 
              <a v-for="(innerItem,innerIndex) in item" class="box-center" @click="go(innerItem)">{{innerItem}}</a>
            </div>
          </div>
          <div v-if="swiperIndex === 5" style="width:100%">
            <div style="width:50%">
              <x-button type="primary" action-type="button" @click.native="myCatgory">我的分类</x-button>
              <x-button type="primary" action-type="button" @click.native="exportData">导出备注</x-button>
              <x-button type="primary" action-type="button" @click.native="importData">导入备注</x-button>
            </div>
            <textarea style="width:100%;text-indent:5px;box-sizing: border-box;margin:10px auto;height:160px;border:1px solid #C7C7C7" placeholder="备注" v-model="remark"></textarea>
          </div>
        </swiper-item>
      </swiper>
        
    </div>
    <confirm v-model="isShowRemarkConfirm"
    ref="confirm2"
    :title="remarkConfirmTitle"
    @on-confirm="saveRemark">

    </confirm> 
  </div>
</template>



<script>
import Vue from 'vue'

import { Tab, TabItem,Swiper, SwiperItem,Loading,XButton, Confirm } from 'vux'  

export default {
  components: {
    Tab,
    TabItem,
    Swiper, 
    SwiperItem,
    Loading,
    XButton,
    Confirm
    
  },
  data () {
    return {
        isShowRemarkConfirm: false,
        remarkConfirmTitle: '确定要导入吗？',
        remark:'',
        type : 0,
        tabIndex : 0,
        tabList : ["翻屏模式","列表模式","中英练习","英中练习","句子练习","自定义"],
        list :[
                ["A","B","C","D","E"],
                ["F","G","H","I","J"],
                ["K","L","M","N","O"],
                ["P","Q","R","S","T"],
                ["U","V","W","X","Y"],
                ["Z"]
              ],



    }
  },
  created(){
    var that = this;
    that.$vux.loading.hide();
    if(navigator.userAgent.toLowerCase().match("windows") !== null){
      this.$vux.toast.show({
       text: '使用手机或平板浏览此网站效果更佳',
       type: "text",
       width: '600px',
       position: 'top',
       time: 20 * 1000
      })
    }
    
  },
  methods: {
    exportData: function() {
      var that = this
      that.remark = localStorage.getItem('remark')
    },
    importData: function() {
      var that = this
      if(!that.remark || that.remark === 'null'){
        this.$vux.toast.show({
         text: '导入内容为空',
         type: "text",
         position: 'top',
        })
        return
      }
      that.isShowRemarkConfirm = true
    },
    saveRemark: function() {
      var that = this
      localStorage.setItem("remark",that.remark)
      this.$vux.toast.show({
       text: '导入成功',
       type: "text",
       position: 'top',
      })
    },
    myCatgory: function(){
      var that = this;
      that.$router.push({ name: 'myWord'})
    },
    go : function(value){
      var that = this;
      value = value.toLowerCase();
      if(that.type === 0){
        that.$router.push({ name: 'word2', params: { name:value,type:that.type}})
      }
      else if(that.type < 4 && that.type >= 1){
        that.$router.push({ name: 'word', params: { name:value,type:that.type}})
      }
      else if(that.type === 4){
        that.$router.push({ name: 'sentenceSelect', params: { name:value}})
      }
      
    },
    tabChange : function(index){
      this.type = index;
    },
    changeSwiper : function(index){
      this.type = index;
    }
  }
}
</script>
<style lang="less">
  html,body,#app,.index-container{
    height:100%;
  }
  body{
    background-color:#fff;
  }
  body .vux-tab .vux-tab-item{
    font-size:16px;
  }
  body .vux-slider{
    height:100%;
  }
  html body .weui-btn_primary {
      background-color: #1AAD19;
      color:#fff;
      padding:5px 20px;
  }
</style>
<style lang="less" scoped>
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/center.less';
@import '../css/common.css';
.index-container .index-main{
  padding:0 10px;
  height:350px;
}
h1{
  font-size:18px;
  padding:10px;
  text-align:center;
}
h1 strong{
  color:#EB2F2F;

}
h1 em{
    font-style: normal;
    color:rgb(82, 163, 227);
  }
.item-wrapper{
  width:100%;
}
.item{
  margin-bottom:20px;
  width:100%;
}
.item a{
  width:15%;
  height:auto!important;
  font-size:20px;
  color:#3A3A3A;
  border:solid 1px #52A3E3;
  border-radius:10px;
}
.tab-header{
  height:44px;
}  
</style>
