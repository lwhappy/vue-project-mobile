<template>
  <div class="tab-container box-v-start align-stretch">
    <div class="tab-header">
      <tab :line-width=2 active-color='#52A3E3' v-model="swiperIndex">
        <tab-item class="vux-center" :selected="index === swiperIndex" v-for="(value,index) in list1"  :key="index" badge-background="#38C972"  >
          <div class="box-center color2">
            <p>{{value}}</p>
            <p class="circle box-center">{{list2[value].list.length}}</p>
          </div>
        </tab-item>
      </tab>
    </div>  
    <div class="rest" style="overflow:auto">
      
        <swiper v-model="swiperIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper">
          <!--<scroller class="scroll-wrapper" :on-refresh="refresh"
               :on-infinite="infiniteHandler"
               style="padding-top: 44px;">-->
          <swiper-item v-for="(value,key,outerIndex) in list2" :key="outerIndex" :selected="outerIndex===swiperIndex">
            <!--<div v-show="outerIndex !== swiperIndex" style="height:100%"><loading :show="showLoading" text="Loading"></loading></div>-->
            <div> 
              <div v-for="(item, index) in value.list" :key="index" class="tab-swiper  vux-1px-b">
                <div v-if="type === 0" class="type0" >
                  <div  class="item-row item-row1">
                    <div class="size1 box-start">
                        <p class="num color3 box-end">{{index + 1}}.</p>
                        <p class="color1 en-size1">{{item.word_name}}</p>
                        <p class="color2 size2 ellipsis rest">&nbsp;&nbsp;美&nbsp;[{{item.ph_am}}] </p>
                    </div>
                  </div>
                  <div class="item-row item-row2 ">
                    
                    <div class="item-left">
                      <p class="ellipsis color2 size2 ">{{item.means}}</p>
                      <p @click="showSentence(item)" class="ellipsis color2 size2">例句</p>
                      <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_en}}</p>
                      <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_cn}}</p>
                    </div>
                  </div>
                </div>
                 <div class="type1" v-if="type === 1" >
                   <div class="item-row item-row1 box-start">
                    <p class="num color3 ">{{index + 1}}.</p>
                    <p class="ellipsis color1 size1 rest">{{item.means}}</p>
                   </div>
                   <div  class="box-justify item-row" >
                       
                       <div class="color2  box-justify item-left" >
                          <p class="input-wrapper vux-1px">
                             <input class=" input box-center size1" :readonly="(value.wordModelList[index][0] && value.wordModelList[index][0] === item.word_name) ? true : false" type="text" v-model="list2[key].wordModelList[index][0]">
                          </p>
                          <p @click="showPopupPicker(index,item.word_name)" class="size2" v-show="!value.wordModelList[index][0] || (value.wordModelList[index][0] && value.wordModelList[index][0] !== item.word_name)" >点击选择</p>
                          <!--<p class="size1">{{value.wordModelList[index][0]}}</p>-->
                       </div>
                       <!--<p style="display:none">{{wordModelList[index]}}</p>--><!--没有这行popup-picker的v-model视图不能更新-->
                       
                     <p class="item-right" v-show="value.wordModelList[index][0] && value.wordModelList[index][0] === item.word_name"><icon  type="success"></icon></p>
                     <p class="item-right" v-show="value.wordModelList[index][0] && value.wordModelList[index][0] !== item.word_name"><icon  type="warn"></icon></p>
                   </div>
                   
                 </div>
                 <div class="type2" v-if="type === 2" @click="showPopupPicker(index,item.means)">
                   <div  class="item-row item-row1 box-start">
                     <p class="num color3 ">{{index + 1}}.</p>
                     <p class="color1 en-size1">{{item.word_name}}</p>
                     <p class="color2 size2 rest ellipsis">&nbsp;&nbsp;美&nbsp;[{{item.ph_am}}]</p>
                   </div>
                   <div  class="box-justify item-row item-row2" >
                       <div class="color2 size2 box-justify item-left" >
                          <p v-show="!value.meanModelList[index][0]" >点击选择</p>
                          <p>{{value.meanModelList[index][0]}}</p>
                       </div>
                       <!--<p style="display:none">{{meanModelList[index]}}</p>--><!--没有这行popup-picker的v-model视图不能更新-->
                       
                     <p class="item-right" v-show="value.meanModelList[index][0] && value.meanModelList[index][0] === item.means"><icon  type="success"></icon></p>
                     <p class="item-right" v-show="value.meanModelList[index][0] && value.meanModelList[index][0] !== item.means"><icon  type="warn"></icon></p>
                   </div>
                 </div>
               
               
              </div>
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
      
    </div>
    <!--<div class="bot box-justify color2 size2">
        <p class="box-center" :class="type === 0?'active' : ''" @click="play(0)" >学习模式</p>
        <p class="apart-line vux-1px-l"></p>
        <p class="box-center" :class="type === 1?'active' : ''" @click="play(1)">中英练习</p>
        <p class="apart-line vux-1px-l"></p>
        <p class="box-center" :class="type === 2?'active' : ''" @click="play(2)">英中练习</p>
    </div>-->
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
      type : Number(this.$route.params.type) || 0,
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
    console.log("type",this.type)
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
              wordObj.showSentence = false
              wordObj.symbols = JSON.parse(wordObj.symbols)
              wordObj.means = wordObj.symbols[0]["parts"][0].part + wordObj.symbols[0]["parts"][0].means.join(";")
              wordObj.ph_am = wordObj.symbols[0].ph_am
              wordObj.ph_en = wordObj.symbols[0].ph_en
              that.list2[that.list1[i]].list.push(wordObj)
              that.list2[that.list1[i]].wordList[0].push(wordObj.word_name)
              that.list2[that.list1[i]].meanList[0].push(wordObj.means)
              var l = that.list2[that.list1[i]].meanList[0].length;
              //that.list2[that.list1[i]].wordModelList[l-1]=['']
              //that.list2[that.list1[i]].meanModelList[l-1]=['']

              Vue.set(that.list2[that.list1[i]].wordModelList, l-1, [''])
              Vue.set(that.list2[that.list1[i]].meanModelList, l-1, [''])
            }
          }
        }
        for(var o in that.list2){
          that.list2[o].wordList[0].sort(function(){
            return .5 -Math.random()
          })
          that.list2[o].meanList[0].sort(function(){
            return .5 -Math.random()
          })
        }
        console.log("that.list2",that.list2)
        that.list2Clone = Object.assign({},that.list2)
        console.log("that.list2Clone",that.list2Clone)
        that.currentList = that.list2[that.list1[0]]
        that.currentList.wordList = that.list2[that.list1[that.swiperIndex]].wordList
        that.currentList.meanList = that.list2[that.list1[that.swiperIndex]].meanList
        console.log("that.currentList",that.currentList)

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
        that.$vux.loading.hide()

      })
    
    
  },
  methods: {
      hidePopup : function(){
        var that = this;
        that.isShowPopupPicker = false;
      },
      showPopupPicker : function(index,value){
        var that = this;
        
        that.itemIndex = index;
        if(that.type === 1){
          that.popupTitle = that.currentList.list[that.itemIndex].means;
        }
        else if(that.type === 2){
          that.popupTitle = that.currentList.list[that.itemIndex].word_name;
        }
        console.log(that.popupTitle)
        that.popupData[0].sort(function(){
            return .5 -Math.random()
        })
        var newData = that.popupData[0].slice(0,10);

        var isFind = false;
        for(var i=0,len=newData.length;i<len;i++){
          if(newData[i] === value){
            isFind = true;
            break;
          }
        }
        if(!isFind){
          newData[newData.length-1] = value;
        }
        newData.sort(function(){
            return .5 -Math.random()
        })
        //that.popupData[0] = newData;
        Vue.set(that.popupData, 0, newData)
        that.isShowPopupPicker = true;

        
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
      },
      showSentence : function(item){
        item.showSentence = !item.showSentence;
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
  .bot .apart-line{
    height:50%;
    width:0;
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
.num{
  width:25px;
  font-size:13px;
  text-align:right;
  padding-right:5px;
  line-height: normal;
}
 .size1{
  font-size:16px;
 }
 .en-size1{
  font-size:18px;
 }
 .size2{
  font-size:14px;
 }
  .color1{
    color:#000;
  }
  .color2{
    color:#565454;
  }
  .color3{
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
  .item-row .input-wrapper{
    height:24px;
    width:70%;
    display:block;
    width:70%;
  }
  .item-row .input-wrapper .input{
    background:none;
    border:none;
    padding-left:6px;
    width:100%;
    height:100%;
    position:absolute;
    z-index:10;

  }
  .item-row .input-wrapper .input:focus{
    outline:none;
  }
  .type0 .item-row .item-left{
    padding-right:10px;
    padding-left:30px;
    width:auto;
  }
  .type1 .item-row .item-left,.type2 .item-row .item-left{
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
  .circle{
    background:rgb(56, 201, 114);
    color:#fff;
    font-size:12px;
    width:20px;
    height:20px;
    border-radius:20px;
    text-align:center;
    margin-left:4px;
  }
</style>
