<template>
  <div class="tab-container box-v-start align-stretch">
    <div class="tab-header">
      <tab :line-width=2 active-color='#fc378c' v-model="swiperIndex">
        <tab-item class="vux-center" :selected="key === 'ab'" v-for="(value,key) in list2"  :key="key">{{key}}</tab-item>
      </tab>
    </div>  
    <div class="rest" style="overflow:auto">
      
        <swiper v-model="swiperIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper">
          <!--<scroller class="scroll-wrapper" :on-refresh="refresh"
               :on-infinite="infiniteHandler"
               style="padding-top: 44px;">-->
          <swiper-item v-for="(value,key) in list2" :key="key" :selected="key==='ab'">
            
            <div v-for="(item, index) in list2[key].list" :key="index" class="tab-swiper  bd-bottom">
              <div v-show="type === 0" class="" >
                <div  class="item-row">
                  <p>
                    <span>{{item.word_name}}</span>
                    <span>[{{item.ph_am}}]</span>
                  </p>
                </div>
                <div class="item-row">
                  <p class="ellipsis">{{item.means}}</p>
                </div>
              </div>
               <div v-show="type === 1" >
                 <div  class="box-justify item-row">
                   <group class="popup-picker-wrapper" >
                     <popup-picker title="点击选择" :data="list2[key].wordList" @on-show="showPopup(item,index)" @on-change="changeWordPopup(key,index)" v-model="value.wordModelList[index]"></popup-picker>
                     <p style="display:none">{{wordModelList[index]}}</p><!--没有这行popup-picker的v-model视图不能更新-->
                     
                   </group>
                   <p v-show="value.wordModelList[index][0] && value.wordModelList[index][0] === item.word_name"><icon  type="success"></icon></p>
                   <p v-show="value.wordModelList[index][0] && value.wordModelList[index][0] !== item.word_name"><icon  type="warn"></icon></p>
                 </div>
                 <p class="ellipsis">{{item.means}}</p>
               </div>
               <div v-show="type === 2" >
                 <div  class="item-row">
                   <p>
                     <span>{{item.word_name}}</span>
                     <span>[{{item.ph_am}}]</span>
                   </p>
                 </div>
                 <div  class="box-justify item-row">
                   <group class="popup-picker-wrapper" >
                     <popup-picker title="点击选择" :data="list2[key].meanList" @on-show="showPopup(item,index)" @on-change="changeMeanPopup(key,index)" v-model="value.meanModelList[index]"></popup-picker>
                     <p style="display:none">{{meanModelList[index]}}</p><!--没有这行popup-picker的v-model视图不能更新-->
                     
                   </group>
                   <p v-show="value.meanModelList[index][0] && value.meanModelList[index][0] === item.means"><icon  type="success"></icon></p>
                   <p v-show="value.meanModelList[index][0] && value.meanModelList[index][0] !== item.means"><icon  type="warn"></icon></p>
                 </div>
               </div>
             
             
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
      
    </div>
    <div class="bot">
      <button-tab>
        <button-tab-item @on-item-click="play(0)" selected>learn</button-tab-item>
        <button-tab-item @on-item-click="play(1)">word</button-tab-item>
        <button-tab-item @on-item-click="play(2)">mean</button-tab-item>
      </button-tab>
    </div>
  </div>
</template>



<script>
import Vue from 'vue'
import VueScroller from 'vue-scroller'
Vue.use(VueScroller)  
import axios from 'axios';
import word from '../js/cet4/cet4-a.js';
console.log(word)
const api = '/static/ajax-data/infinite-loading.txt';
import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem, Datetime, Selector, Marquee, MarqueeItem, Toast , PopupPicker , ButtonTab, ButtonTabItem,Icon } from 'vux'
const list1 = () => [
      'ab', 
      'ac', 
      'ap', 
      'ar', 
      'at'
  ]
  

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
    Icon 
  },
  data () {
    return {
      type : 0,
      index01: 0,
      list1: list1(),
      list2: {},
      list2Clone:{},
      swiperIndex: 0,
      demo2: '标题2',

      asyncCount : 5,
      wordModelList : {},
      meanModelList : {}
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
    
    for(var i = 0,len=that.list1.length;i<len;i++){
      that.list2[that.list1[i]] = {
        list : [],
        wordList : [[]],
        meanList : [[]],
        wordModelList : [],
        meanModelList : []
      }
    }
    for(var o in word.words){
      for(var i=0,len=that.list1.length;i<len;i++){
        var strLen = that.list1[i].length;
        if(o.substr(0,strLen) === that.list1[i]){
          var wordObj = JSON.parse(word.words[o])
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
  },
  methods: {
      play : function(type){
        var that =  this;
        that.type = type;
        that.list2 = Object.assign({},that.list2Clone)
        switch(type){
          case 0:
            
            break;
          case 1:
            for(var o in that.list2){
              that.list2[o].wordList[0].sort(function(){
                return .5 - Math.random();
              })
              that.list2[o].wordModelList = Object.assign([],that.list2Clone[o].wordModelList)
              
            }
            that.wordModelList = {}
            break;
           case 2:
            for(var o in that.list2){
              that.list2[o].meanList[0].sort(function(){
                return .5 - Math.random();
              })
              that.list2[o].meanModelList = Object.assign([],that.list2Clone[o].meanModelList)
            }
            console.log("that.list2",that.list2)
            console.log("that.list2Clone",that.list2Clone)
            that.meanModelList = {}
            break;
        }

      },
      changeSwiper : function(){
        var that = this
        console.log(that.swiperIndex)
        that.wordModelList = {}
      },
      showPopup : function(item,index){
        console.log(index,item)
      },
      changeWordPopup : function(key,index){
        var that = this;
        console.log("that.list2[key].wordModelList[index]",that.list2[key].wordModelList[index])
        var wordModelList = Object.assign([],that.list2[key].wordModelList[index])
        Vue.set(that.wordModelList, index, wordModelList)//没有这行popup-picker的v-model视图不能更新
        console.log("that.list2",that.list2)
        console.log("that.list2Clone",that.list2Clone)
      },
      changeMeanPopup : function(key,index){
        var that = this;

        var meanModelList = Object.assign([],that.list2[key].meanModelList[index])
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
  ._v-content{
    height:100%;
  }
  .popup-picker-wrapper .weui-cells:before,.popup-picker-wrapper .weui-cells:after{
    border:none;
  }  
</style>
<style lang="less" scoped>
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/center.less';
@import '../css/common.css';
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
    width:100%;
    padding:5px 10px 10px;
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
