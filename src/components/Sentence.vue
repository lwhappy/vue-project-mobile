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
    <div class="rest" style="overflow:auto;">
      
        <swiper v-model="swiperIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper">
          <!--<scroller class="scroll-wrapper" :on-refresh="refresh"
               :on-infinite="infiniteHandler"
               style="padding-top: 44px;">-->
          <swiper-item v-for="(value,key,outerIndex) in list2" :key="outerIndex" :selected="outerIndex===swiperIndex">
            <!--<div v-show="outerIndex !== swiperIndex" style="height:100%"><loading :show="showLoading" text="Loading"></loading></div>-->
            <div> 
              <div v-for="(item, index) in value.sentences" :key="index" class="tab-swiper  vux-1px-b" >
                <div  class="type0" >
                  <div  class="item-row item-row1">
                    <div class="size1 box-start">
                        <p class="num color3 box-end">{{item.num}}.</p>
                        <p class="color1 en-size1" >{{item.Network_en}}</p>
                        
                    </div>
                  </div>
                  <div class="item-row item-row2 ">
                    
                    <div class="item-left">
                      <p class="color1 en-size1" >{{item.Network_cn}}</p>
                      <p class="color2 size3 ellipsis rest">英&nbsp;[{{value.list[index].ph_en}}] &nbsp;美&nbsp;[{{value.list[index].ph_am}}]</p>
                      <p class=" color2 size3 ">{{value.list[index].means}}</p>

                      <!--<p @click="showSentence(item)" class="ellipsis color2 size2">例句</p>
                      <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_en}}</p>
                      <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_cn}}</p>-->

                    </div>
                  </div>
                </div>
                 
               
               
              </div>
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
      
    </div>
   
    <popup-picker style="display:none"  class="size2 color2" :show="isShowPopupPicker" title="点击选择" :data="popupData" @on-hide="hidePopup()" @on-show="showPopup()" @on-change="changeWordPopup()" v-model="popupValue" :popup-title="popupTitle">
      
    </popup-picker><!--只初始化一个-->
   
  </div>
</template>



<script>
import Vue from 'vue'
import axios from 'axios';
//import config from '../js/cet4/config.js';
//console.log("config",config)

import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem, Datetime, Selector, Marquee, MarqueeItem, Toast , PopupPicker , ButtonTab, ButtonTabItem,Icon ,Loading,Popover,XInput,Confirm,TransferDomDirective as TransferDom, ConfirmPlugin } from 'vux'
Vue.use(ConfirmPlugin)

export default {
  directives: {
    TransferDom
  },
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
    Loading,
    Popover,
    XInput,
    Confirm 
  },
  data () {
    return {
      newCategory: '',
      activeWord: null,
      activeCategory: '',
      myCategory: [],
      confirmTitle: '添加到我的分类',
      isShowConfirm: false,
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
      currentListClone : [],
      isShowPopupPicker : false,
      popupValue : [""],
      itemIndex : "",
      popupData : [],
      popupTitle : "",
      isSearch : false,
      isSearchPicker : false,
      searchData : [],
      searchValue : [""],
      group : {},
      searchInputValue : "",
      keybord:["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"],
      isShowRight : false,
      readonly : true,
               
      /*english : {
        wordList : [[]],
        meanList : [[]],
        wordModelList : []
      },
      popupValue : [""]*/

    }
  },
  watch : {
    
  },
  filters : {
    
  },
  created(){
    console.log("type",this.type)
    var that = this;
    var name = that.$router.history.current.params.name;
    const api = 'static/cet4/cet4-'+name+'.js';
    const sentenceApi = 'http://localhost/word/php/get-sentence.php'
    
    //that.firstKey = that.list1[0];
    //console.log(that.$router.history.current.params.name)
    
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
        var tabStr = "";
        for(var o in words){
          var temp = o.substr(0,2)
          if(temp !== tabStr){
            that.list1.push(temp);
            tabStr = temp;
          }
        }
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
            sentences : [],
            
          }
          Vue.set(that.list2, that.list1[i], obj)
        }
        for(var o in words){
          for(var i=0,len=that.list1.length;i<len;i++){
            var tabItem = that.list1[i];
            var strLen = tabItem.length;
            if(o.substr(0,strLen) === tabItem){
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
              wordObj.isShow = true;
              wordObj.num = that.list2[tabItem].list.length + 1;
              that.list2[tabItem].list.push(wordObj)


            }
          }
        }
        var firstKey = that.list1[0]
        var list = that.list2[firstKey].list
        var wordArr = []
        for(var i=0,len=list.length;i<len;i++){
          wordArr.push(list[i].word_name)
        }
        console.log(wordArr)
      
        //obj = JSON.stringify(obj)
        //wordArr = JSON.stringify(wordArr)
        let param = new URLSearchParams()
        param.append('words', JSON.stringify(wordArr))
        axios({
            url:sentenceApi,
            method: 'post',
            data: param,
            headers:{
            }
            
          })
          .then(response=>{
            console.log(response.data)
            that.list2[firstKey].sentences = response.data
          })
        console.log("that.list2",that.list2)
        that.list2Clone = Object.assign({},that.list2)
        console.log("that.list2Clone",that.list2Clone)
        that.currentList = that.list2[firstKey]

        console.log("that.currentList",that.currentList)

        
        that.currentListClone = Object.assign({},that.currentList);
        that.$vux.loading.hide()

      })
    
    
  },
  methods: {
      
      
     
      hidePopup : function(){
        var that = this;
        that.isShowPopupPicker = false;
      },
      hideSearchPopup : function(){
        var that = this;
        that.isSearchPicker = false;
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
        var newData = that.popupData[0].slice(0,6);

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
      
      changeSwiper : function(index){
        var that = this;
        that.isSearch = false;
        that.isSearchPicker = false;
        that.searchInputValue = "";
        console.log("that.list2Clone",that.list2Clone)
        that.currentList.list = Object.assign([],that.currentListClone.list);
        //searchInputValue新旧值相同时不会触发watch
        for(var i=0,len=that.currentList.list.length;i<len;i++){
          that.currentList.list[i].isShow = true;
          that.currentList.list[i].num = i+1;
         
        }
        that.swiperIndex = index;
        console.log(that.swiperIndex)
        if(that.group){
          that.searchData = that.group[that.list1[that.swiperIndex]];
        }
        
        that.currentList = that.list2[that.list1[index]];
        that.currentListClone = Object.assign({},that.currentList);
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
    padding:10px 0 15px 0;
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
  html body .vux-popover .popover-content{
    padding:3px 6px;
  }
  html body .weui-icon-search{
    font-size:24px;
  }
  .vux-swiper-item .tab-swiper:last-of-type{
    margin-bottom:50px;
  }
  
  html body .weui-icon-clear{
    display:block!important;
  }
</style>
<style lang="less" scoped>
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/center.less';
@import '../css/common.css';



.num{
  width:20px;
  font-size:13px;
  text-align:right;
  padding-right:5px;
  line-height: normal;
}
 .size1{
  font-size:18px;
 }
 .en-size1{
  font-size:20px;
  font-weight:600;
 }
 .size2{
  font-size:16px;
 }
 .size3{
  font-size:14px;
 }
  .color1{
    color:#000;
  }
  .color2{
    color:#565454;
  }
  .color3{
    color:#908e8e;
  }
  html body .search-word-color{
    color:#4040d4;
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
    /*margin-bottom:5px;*/
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
    height:30px;
    width:65%;
    margin-right:5px;
    display:block;
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
    padding-left:25px;
    width:76%;
  }
  .type0 .item-row .item-left p:first-of-type{
    margin-bottom:7px;
  }
  .type0 .item-row .item-left .sentence{
    line-height:20px;
  }
  .type0 .item-row .item-left .sentence1{
      padding:7px 0 2px 0;
      
  }
  .type1 .item-row1{
    margin-bottom:5px;
    width:80%;
  } 
  .type1 .item-row .item-left,.type2 .item-row .item-left{
    width: 65%;
    padding-left: 25px;
  }
  .type1 .item-row .item-left{
    width:70%;
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
  #right{
    /*width:40%;*/
    height:100%;
    position:fixed;
    right:10px;
    top:0;
    z-index:100;
    
  }
 
  .inner-right-wrapper{
    width:100%;
    height:100%;
    overflow:auto;
  }
  .inner-right-wrapper .inner-empty{
    height:44px;
    width:100%;
  }
  .inner-right-wrapper .inner-right{
    overflow:auto;
  }
  .inner-right .icon-wrapper{
    position:relative;
  }
  .inner-right .icon-wrapper .icon-name{
    position:absolute;
    left:0;
    top:9px;
    width:100%;
    text-align:center;
    color:#000;
    font-size:12px;
  }
  .inner-right .icon-wrapper .icon-name.current-name{
    color:#fff;
  }
  
  
</style>
