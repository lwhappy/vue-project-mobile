<template>
  <div class="tab-container box-v-start align-stretch">
    <div class="tab-header">
      <tab :line-width=2 active-color='#52A3E3' v-model="swiperIndex">
        <tab-item class="vux-center" :selected="index === swiperIndex" v-for="(value,index) in list1"  :key="index" badge-background="#38C972" badge-color="#fff" :badge-label="list2[value].list.length.toString()">{{value}}</tab-item>
      </tab>
    </div>  
    <div class="rest" style="overflow:auto">
      
        <swiper v-model="swiperIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper">
          <!--<scroller class="scroll-wrapper" :on-refresh="refresh"
               :on-infinite="infiniteHandler"
               style="padding-top: 44px;">-->
          <swiper-item v-for="(value,outerIndex) in list1" :key="outerIndex" :selected="outerIndex===swiperIndex">
            <div v-show="outerIndex !== swiperIndex" style="height:100%"><loading :show="showLoading" text="Loading"></loading></div>
            <div v-show="outerIndex === swiperIndex"> 
              <div v-for="(item, index) in currentList.list" :key="index" class="tab-swiper  bd-bottom">
                <div v-if="type === 0" class="" >
                  <div  class="item-row item-row1">
                    <div class="size1 box-start">
                      <p class="num color2">{{index + 1}}.</p>
                      <p class="color1">{{item.word_name}}</p>
                      <p class="color2">[{{item.ph_am}}]</p>
                    </div>
                  </div>
                  <div class="item-row item-row2">
                    <p class="ellipsis color2 size2">{{item.means}}</p>
                  </div>
                </div>
                 <div v-if="type === 1" @click="showPopupPicker(index)">
                   <div class="item-row item-row1 box-start">
                    <p class="num color2">{{index + 1}}.</p>
                    <p class="ellipsis color1 size1">{{item.means}}</p>
                   </div>
                   <div  class="box-justify item-row" >
                       
                       <div class="color2 size2 box-justify item-left" >
                          <p v-show="!currentList.wordModelList[index][0]" >点击选择</p>
                          <p>{{currentList.wordModelList[index][0]}}</p>
                       </div>
                       <!--<p style="display:none">{{wordModelList[index]}}</p>--><!--没有这行popup-picker的v-model视图不能更新-->
                       
                     <p class="item-right" v-show="currentList.wordModelList[index][0] && currentList.wordModelList[index][0] === item.word_name"><icon  type="success"></icon></p>
                     <p class="item-right" v-show="currentList.wordModelList[index][0] && currentList.wordModelList[index][0] !== item.word_name"><icon  type="warn"></icon></p>
                   </div>
                   
                 </div>
                 <div v-if="type === 2" @click="showPopupPicker(index)">
                   <div  class="item-row item-row1 box-start">
                     <p class="num color2">{{index + 1}}.</p>
                     <p>
                       <span>{{item.word_name}}</span>
                       <span>[{{item.ph_am}}]</span>
                     </p>
                   </div>
                   <div  class="box-justify item-row item-row2" >
                       <div class="color2 size2 box-justify item-left" >
                          <p v-show="!currentList.meanModelList[index][0]" >点击选择</p>
                          <p>{{currentList.meanModelList[index][0]}}</p>
                       </div>
                       <!--<p style="display:none">{{meanModelList[index]}}</p>--><!--没有这行popup-picker的v-model视图不能更新-->
                       
                     <p class="item-right" v-show="currentList.meanModelList[index][0] && currentList.meanModelList[index][0] === item.means"><icon  type="success"></icon></p>
                     <p class="item-right" v-show="currentList.meanModelList[index][0] && currentList.meanModelList[index][0] !== item.means"><icon  type="warn"></icon></p>
                   </div>
                 </div>
               
               
              </div>
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
      
    </div>
    <div class="bot box-justify">
        <p class="box-center" :class="type === 0?'active' : ''" @click="play(0)" >学习模式</p>
        <p class="box-center" :class="type === 1?'active' : ''" @click="play(1)">中英练习</p>
        <p class="box-center" :class="type === 2?'active' : ''" @click="play(2)">英中练习</p>
    </div>
    <popup-picker style="display:none"  class="size2 color2" :show="isShowPopupPicker" title="点击选择" :data="popupData" @on-hide="hidePopup()" @on-show="showPopup()" @on-change="changeWordPopup()" v-model="popupValue" :popup-title="popupTitle">
      
    </popup-picker><!--只初始化一个-->
  </div>
</template>



<script>
import Vue from 'vue'
import VueScroller from 'vue-scroller'
Vue.use(VueScroller)  
import axios from 'axios';
import config from '../js/cet4/config.js';
console.log("config",config)

import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem, Datetime, Selector, Marquee, MarqueeItem, Toast , PopupPicker , ButtonTab, ButtonTabItem,Icon ,Loading} from 'vux'


export default {
  components: {
    Tab,
    TabItem,
    Sticky,
    Divider,
    XButton,
    Swiper,
    SwiperItem,
    Datetime,
    Selector,
    Marquee,
    MarqueeItem,
    Toast,
    PopupPicker,
    ButtonTab, 
    ButtonTabItem,
    Icon ,
    Loading
  },
  data () {
    return {
      type : 0,
      index01: 0,
      list1: [],
      list2: {},
      list2Clone:{},
      swiperIndex: 0,
      firstKey: '',
      showLoading:true,
      asyncCount : 5,
      currentList : [],
      isShowPopupPicker : false,
      popupValue : [""],
      itemIndex : "",
      popupData : [],
      popupTitle : "",
      /*english : {
        wordList : [[]],
        meanList : [[]],
        wordModelList : []
      },
      popupValue : [""]*/

    }
  },
  created(){
    var that = this;
    var name = that.$router.history.current.params.name;
    const api = 'static/cet4/cet4-'+name+'.js';
    that.list1 = config.all.words[name].tab;
    //that.firstKey = that.list1[0];
    //console.log(that.$router.history.current.params.name)
    for(var i = 0,len=that.list1.length;i<len;i++){
      /*that.list2[that.list1[i]] = {
        list : [],
        wordList : [[]],
        meanList : [[]],
        wordModelList : [],
        meanModelList : []
      }*/
      var obj = {
        list : [],
        wordList : [[]],
        meanList : [[]],
        wordModelList : [],
        meanModelList : []
      }
      Vue.set(that.list2, that.list1[i], obj)
    }
    that.$vux.loading.show({
      text: 'Loading'
    })
    axios({
        url:api,
        method: 'get',
        data: "",
        headers:{
          
        }
        
      })
      .then(response=>{
        console.log("response",response);
        var words = response.data;
        for(var o in words){
          for(var i=0,len=that.list1.length;i<len;i++){
            var strLen = that.list1[i].length;
            if(o.substr(0,strLen) === that.list1[i]){
              try{
                var wordObj = JSON.parse(words[o])
              
              }catch(e){
                console.log(e,words[o])
                continue;
              }

              wordObj.sentence = JSON.parse(wordObj.sentence)
              wordObj.symbols = JSON.parse(wordObj.symbols)
              wordObj.means = wordObj.symbols[0]["parts"][0].means.join(";")
              wordObj.ph_am = wordObj.symbols[0].ph_am
              that.list2[that.list1[i]].list.push(wordObj)
              that.list2[that.list1[i]].wordList[0].push(wordObj.word_name)
              that.list2[that.list1[i]].meanList[0].push(wordObj.means)
              var l = that.list2[that.list1[i]].meanList[0].length;
              that.list2[that.list1[i]].wordModelList[l-1]=['']
              that.list2[that.list1[i]].meanModelList[l-1]=['']
            }
          }
        }
        console.log(that.list2)
        that.list2Clone = Object.assign({},that.list2)
        console.log("that.list2Clone",that.list2Clone)
        console.log(that.list1[0])
        that.currentList = that.list2[that.list1[0]]
        that.currentList.wordList = that.list2[that.list1[that.swiperIndex]].wordList
        that.currentList.wordList[0].sort(function(){
          return .5 -Math.random()
        })
        that.currentList.meanList = that.list2[that.list1[that.swiperIndex]].meanList
        that.currentList.meanList[0].sort(function(){
          return .5 -Math.random()
        })
        console.log("that.currentList",that.currentList)
        that.$vux.loading.hide()

      })
    
    
  },
  methods: {
      hidePopup : function(){
        var that = this;
        that.isShowPopupPicker = false;
      },
      showPopupPicker : function(index){
        var that = this;
        that.isShowPopupPicker = true;
        that.itemIndex = index;
        if(that.type === 1){
          that.popupTitle = that.currentList.list[that.itemIndex].means;
        }
        else if(that.type === 2){
          that.popupTitle = that.currentList.list[that.itemIndex].word_name;
        }
        console.log(that.popupTitle)
        
      },
      play : function(type){
        var that =  this;
        that.type = type;
        switch(type){
          case 0:
            
            break;
          case 1:
            
            that.popupData = that.currentList.wordList;
            break;
           case 2:
           
            
            that.popupData = that.currentList.meanList;
            break;
        }

      },
      changeSwiper : function(index){
        var that = this
        that.swiperIndex = index;
        console.log(that.swiperIndex)
        that.currentList = that.list2[that.list1[index]];
        console.log(that.currentList)
        switch(that.type){
          case 0:
            
            break;
          case 1:
            
            that.popupData = that.currentList.wordList;
            break;
           case 2:
           
            
            that.popupData = that.currentList.meanList;
            break;
        }
      },
      showPopup : function(){
      },
      changeWordPopup : function(){
        var that = this;
        if(that.type === 1){
          that.currentList.wordModelList[that.itemIndex] = that.popupValue;
        }
        else if(that.type === 2){
          that.currentList.meanModelList[that.itemIndex] = that.popupValue;
        }
        
        console.log("that.list2",that.list2)
        console.log("that.list2Clone",that.list2Clone)
      },
      changeMeanPopup : function(index){
        var that = this;

        var meanModelList = Object.assign([],that.currentList.meanModelList[index])
        Vue.set(that.meanModelList, index, meanModelList)//没有这行popup-picker的v-model视图不能更新
        console.log("that.list2",that.list2)
        console.log("that.list2Clone",that.list2Clone)
      }

   
   
  }
}
</script>
<style lang="less">
  html,body,#app{
    height:100%;
  }
  body{
    background-color:#fff;
  }
  .num{
    width:30px;
  }
  ._v-content{
    height:100%;
  }
  .popup-picker-wrapper .weui-cells:before,.popup-picker-wrapper .weui-cells:after{
    border:none;
  }  

  .bot{
    background:#F6F6F6;
    height:44px;
  }

  html body .tab-swiper{
    padding:10px 0;
  }
  html body .vux-no-group-title{
    margin-top:0;
  }
  html body .weui-cell{
    padding:5px 0;
  }
  .bot p{
    color:#3A3A3A;
    width:33%;
    height:100%;
    position:relative;
  }
  .bot p +p:before{
    content:"";
    position:absolute;
    left:0;
    height:70%;
    width:1px;
    background-color:#E7E7E7;
  }
  .bot p.active{
    color:#52A3E3;
  }
  html body .vux-tab .vux-tab-item{
    font-size:16px;
  }
  .vux-popup-header-title{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    padding: 0 5px;
  }
</style>
<style lang="less" scoped>
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/center.less';
@import '../css/common.css';
 .size1{
  font-size:16px;
 }
 .size2{
  font-size:14px;
 }
  .color1{
    color:#3A3A3A;
  }
  .color2{
    color:#B6B6B6;
  }
  h1{
    height:30px;
    font-size:16px;
    text-align:center;
  }
  .popup-picker-wrapper{
    width:100%;
  }
  
  .field{
    text-align:center;
    width:15%;
    font-size:14px;
  }
  .field-date{
    width:26%;
  }
  .field-status{
    width:25%;
  }
  .tab-container{
    height:100%;
  }
  .tab-container .tab-header{
    height:44px;
  }
  
  .vux-slider{
    height:100%;
  }
  .vux-slider > .vux-swiper > .vux-swiper-item{
    
  }
  .tab-swiper{
    
    
    position:relative;
  }
  .item-key,.item-row{
    padding:0 10px;
  }
  .item-row1{
    margin-bottom:5px;
  }
  .item-operate{
    width:100%;
    /*position:absolute;
    left:0;
    top:50px;
    z-index:5;*/
    height:30px;
    background-color:#afafb5;
    
  }
  .item-operate .operate-row{
    height:100%;
    font-size:12px;
  }
  .item-operate .operate-row .operate-btn{
    padding:0 10px;
  }
  .item-row .field-date{
    /*background:url(../assets/pencil.png) right center no-repeat;*/
  }
  .item-row .item-left{
    width: 80%;
    padding-left: 30px;
  }
  .notice{
    height:50px;
    padding:0 10px;
    .notice-label{
      width:80px;
    }
  }
  .scroll-wrapper{
    position:static;
    
  }
  .vux-slider > .vux-swiper > .vux-swiper-item{
    overflow-x:hidden;
    overflow-y:auto;
  }
  
</style>
