<template>
  <div class="tab-container box-v-start align-stretch">
    <div class="tab-header">
      <tab :line-width=2 active-color='#52A3E3'>
        <tab-item class="vux-center" :selected="index === labelIndex" v-for="(value,index) in list1"  :key="index" badge-background="#38C972" @on-item-click="selectLabel(value,index)" >
          <div class="box-center color2">
            <p>{{value}}</p>
            <p v-if="list2[value] && list2[value].list" class="circle box-center">{{list2[value].list.length}}</p>
          </div>
        </tab-item>
      </tab>
    </div>  
    <div class="rest" style="overflow:auto;">
      
        <swiper v-if="activeKey && list2[activeKey] && list2[activeKey].list" v-model="swiperIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper">
          <!--<scroller class="scroll-wrapper" :on-refresh="refresh"
               :on-infinite="infiniteHandler"
               style="padding-top: 44px;">-->
          <swiper-item  v-for="(item, index) in list2[activeKey].list" :key="index" :selected="index===swiperIndex">
            <!--<div v-show="outerIndex !== swiperIndex" style="height:100%"><loading :show="showLoading" text="Loading"></loading></div>-->
            <div style="height:100%;overflow:hidden"> 
              <div class="tab-swiper box-v-center" style="height:100%;" v-show="item.isShow">
                <div  class="item-row item-row1" style="padding:10px">
                  <div class="size1 box-center">
                      <p class="num color3 box-end">{{item.num}}.</p>
                      <p class="color1 en-size1">{{item.word_name}}</p>
                      <p @click="addToCategory(item)" style="margin-left:20px;font-size:20px">+</p>
                      
                  </div>
                  <p class="color2 size3 ellipsis box-start">英&nbsp;[{{item.ph_en}}] &nbsp;美&nbsp;[{{item.ph_am}}]</p>
                  <p class=" color2 size3 box-start" v-html="item.means"></p>
                  
                </div>
                <div class="rest" style="margin-top:20px;overflow:auto;padding:10px">
                  <template v-if="!item.moreSentence">
                    <p  class="sentence sentence1 color3 size3 box-start">{{item.sentence.Network_en}}</p>
                    <p  class="sentence sentence2 color3 size3 box-start">{{item.sentence.Network_cn}}</p>
                    <p class="box-center color3 size3" @click="getMoreSentence(item)">more</p>
                  </template>
                  <div v-if="item.moreSentence" style="overflow:auto;">
                    <div v-for="(sentenceItem,sentenceIndex) in item.moreSentence" :key="sentenceIndex" class="sentence sentence2 color3 size3 box-v-center align-start" style="padding:10px 0">
                      <p>{{sentenceItem.Network_en}}</p>
                      <p>{{sentenceItem.Network_cn}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
    </div>
    <div v-transfer-dom>
      <confirm v-model="isShowConfirm"
      ref="confirm1"
      :title="confirmTitle"
      @on-cancel="onCancel"
      @on-confirm="onConfirm"
      @on-show="onShow"
      @on-hide="onHide">
        <div style="max-height:300px;overflow:auto">
          <div style="height:24px" v-for="(categoryItem,categoryIndex) in myCategory" :key="categoryItem.createTime" class="box-justify" @click="selectCategory(categoryItem)">
            <p>{{categoryItem.name}}</p>
            <x-icon v-show="categoryItem === activeCategory" style="fill:#F70968;"   type="ios-checkmark-empty" size="28" ></x-icon>
          </div>
          <div class="box-start">
            <group><x-input v-model="newCategory"></x-input></group><p style="width:50px" @click="addNewCategory">添加</p>
          </div>
        </div>
      </confirm>
    </div>
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
      sentenceApi : 'http://www.pangfanqie.com/word/php/get-oneword-sentence.php',
      labelIndex: 0,
      activeKey: '',
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
    searchInputValue : function(val,oldVal){
      var that = this;
      console.log(val)
      that.currentList.list = that.currentListClone.list;
        
      if(val){
        var num = 0;
        for(var i=0,len=that.currentList.list.length;i<len;i++){
          that.currentList.list[i].isShow = false;
          if(that.currentList.list[i].word_name.match(val) !== null){
            that.currentList.list[i].isShow = true;
            num ++;
            that.currentList.list[i].num = num;
            //list.push(that.currentList.list[i]);
          }
        }
      }
      else{
        for(var i=0,len=that.currentList.list.length;i<len;i++){
          that.currentList.list[i].isShow = true;
          that.currentList.list[i].num = i+1;
         
        }
      }
    }
  },
  filters : {
    
  },
  created(){
    console.log("type",this.type)
    var that = this;
    var name = that.$router.history.current.params.name;
    const api = 'static/cet4/cet4-'+name+'.js';
  
    var myCategory = localStorage.getItem('myCategory')
    try {
      myCategory = JSON.parse(myCategory)
    } catch(e){
      console.log(e)
    }
    if(myCategory instanceof Array){
      that.myCategory = myCategory
    }
    
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
        that.activeKey = that.list1[0]
        console.log(that.activeKey)
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
              wordObj.means = ''
              for (var j = 0; j < wordObj.symbols[0]["parts"].length; j++) {
                var temp = wordObj.symbols[0]["parts"][j].part + wordObj.symbols[0]["parts"][j].means.join(";")
                if (j < wordObj.symbols[0]["parts"].length - 1) {
                  temp += '<br>'
                }
                wordObj.means += temp
              }
              // wordObj.means = wordObj.symbols[0]["parts"][0].part + wordObj.symbols[0]["parts"][0].means.join(";")
              wordObj.ph_am = wordObj.symbols[0].ph_am
              wordObj.ph_en = wordObj.symbols[0].ph_en
              wordObj.isShow = true;
              wordObj.num = that.list2[tabItem].list.length + 1;
              that.list2[tabItem].list.push(wordObj)
              that.list2[tabItem].wordList[0].push(wordObj.word_name)
              that.list2[tabItem].meanList[0].push(wordObj.means)
              var l = that.list2[tabItem].meanList[0].length;
              //that.list2[that.list1[i]].wordModelList[l-1]=['']
              //that.list2[that.list1[i]].meanModelList[l-1]=['']

              Vue.set(that.list2[tabItem].wordModelList, l-1, [''])
              Vue.set(that.list2[tabItem].meanModelList, l-1, [''])
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
          var list = that.list2[o].list;
          that.group[o] = [];
          if(list.length){
            var findStr = list[0].word_name.substr(0,3);
            that.group[o].push(findStr);
            for(var i=1,len=list.length;i<len;i++ ){
              var str = list[i].word_name.substr(0,3);
              if(str !== findStr){
                that.group[o].push(str);
                findStr = str;
              }
            }
          }
          
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
        that.currentListClone = Object.assign({},that.currentList);
        that.$vux.loading.hide()

      })
    
    
  },
  methods: {
      getMoreSentence: function(item) {
        var that = this
        let param = new URLSearchParams()
        param.append('word', item.word_name)
        axios({
            url:that.sentenceApi,
            method: 'post',
            data: param,
            headers:{
            }
            
          })
          .then(response=>{
            console.log(response.data)
            Vue.set(item, "moreSentence", response.data)
            //item.moreSentence = response.data
            that.$vux.loading.hide()
          })
      },
      selectLabel: function(value,index) {
        var that = this
        that.activeKey = value
        that.labelIndex = index
      },
      addNewCategory: function(){
        var that = this

        if(that.newCategory){
          for(var i=0,len=that.myCategory.length;i<len;i++){
            if(that.myCategory[i].name === that.newCategory){
              that.$vux.toast.text('有重名')
              return
            }
          }
          var obj = {
            createTime: new Date().getTime().toString(),
            name: that.newCategory,
            word: []
          }
          that.myCategory.push(obj)
          that.activeCategory = obj
          console.log(that.activeCategory,that.myCategory)
          var myCategory = JSON.stringify(that.myCategory)
          localStorage.setItem('myCategory',myCategory)
          that.newCategory = ''
        }
      },
      selectCategory: function(item) {
        var that = this
        that.activeCategory = item
      },
      onCancel: function(){
        var that = this
        that.activeCategory = ''
      },
      onConfirm: function(name){
         var that = this
         try{
            //that.activeCategory.word = JSON.parse(that.activeCategory.word)
            var activeWord = Object.assign({},that.activeWord)
            delete activeWord.isShow
            delete activeWord.num
            that.activeCategory.word.push(activeWord)
            console.log(that.myCategory)
            var myCategory = JSON.stringify(that.myCategory)
            localStorage.setItem('myCategory',myCategory)
         } catch(e){
            console.log(e)
         }
         
      },
      onHide: function(){

      },
      onShow: function(){
         
      }, 
      addToCategory: function(item) {
        var that = this
        that.isShowConfirm = true
        that.activeWord = item
      },
      backspace : function(){
        var that = this;

        console.log(that.searchInputValue)
        if(that.searchInputValue){
          that.searchInputValue = that.searchInputValue.substr(0,that.searchInputValue.length-1);
        }
      },
      getKey:function(key){
        var that = this;
        key = key.toLowerCase();
        that.searchInputValue += key;
      },
      wordFilter : function(matchStr){
        var that = this;
        that.searchInputValue = matchStr;
        
        
      },
      wordColor : function(a,b) {
        if(b){
          if(a.match(b) !== null){
            var index = a.indexOf(b);
            var str1 = a.substring(0,index );
            if(str1){
              str1 = "<span>" + str1 + "</span>";
            }
            var str2 = a.substring(index+b.length);
            if(str2){
              str2 = "<span>" + str2 + "</span>"
            }
            b = '<span>' + b + '</span>';
            var word = str1 +b + str2;
            console.log(word)
            return word;
          }
          else{
            return a;
          }
        }
        else {
          return a;
        }
      },
      searchAll : function(){
        var that = this;
        that.searchInputValue = "";
        
      },
      showSearch : function(){
        console.log("00")
        var that = this;
        that.isSearch = !that.isSearch;
      },
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
          that.popupTitle = that.popupTitle.replace(/<br>/g,'|')
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

  

  html body .tab-swiper{
    padding:10px 0 15px 0;
  }
  html body .vux-no-group-title{
    margin-top:0;
  }
  html body .weui-cell{
    padding:5px 0;
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
  /*.vux-swiper-item .tab-swiper:last-of-type{
    margin-bottom:44px;
  }*/
  
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
  .bot{
    background:#F6F6F6;
    height:44px;
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
  
</style>
