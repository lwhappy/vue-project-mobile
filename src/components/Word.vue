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
              <div v-for="(item, index) in value.list" :key="index" class="tab-swiper  vux-1px-b" v-show="item.isShow">
                <div v-if="type === 0" class="type0" >
                  <div  class="item-row item-row1">
                    <div class="size1 box-start">
                        <p class="num color3 box-end">{{item.num}}.</p>
                        <p class="color1 en-size1" v-html="wordColor(item.word_name,searchInputValue)"></p>
                        <p @click="addToCategory(item)" style="margin-left:20px;font-size:20px">+</p>
                        
                    </div>
                  </div>
                  <div class="item-row item-row2 ">
                    
                    <div class="item-left">
                      <p class="color2 size3 ellipsis rest">英&nbsp;[{{item.ph_en}}] &nbsp;美&nbsp;[{{item.ph_am}}]</p>
                      <p class=" color2 size3 ">{{item.means}}</p>
                      <!--<p @click="showSentence(item)" class="ellipsis color2 size2">例句</p>
                      <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_en}}</p>
                      <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_cn}}</p>-->
                      <p  class="sentence sentence1 color3 size3">{{item.sentence.Network_en}}</p>
                      <p  class="sentence sentence2 color3 size3">{{item.sentence.Network_cn}}</p>
                    </div>
                  </div>
                </div>
                 <div class="type1" v-if="type === 1" >
                   <div class="item-row item-row1 box-start">
                    <p class="num color3 ">{{item.num}}.</p>
                    <p class=" color1 size3 rest">{{item.means}}</p>
                   </div>
                   <div  class="box-start item-row" >
                       
                       <div class="color2  box-justify item-left" >
                          <p class="input-wrapper vux-1px">
                             <input class=" input box-center size1" :readonly="(value.wordModelList[index][0] && value.wordModelList[index][0] === item.word_name) ? true : false" type="text" v-model="list2[key].wordModelList[index][0]">
                          </p>
                          
                          <p @click="showPopupPicker(index,item.word_name)" class="size3 color3" v-show="!value.wordModelList[index][0] || (value.wordModelList[index][0] && value.wordModelList[index][0] !== item.word_name)" >点击选择</p>
                          <!--<p class="size1">{{value.wordModelList[index][0]}}</p>-->
                       </div>
                       <!--<p style="display:none">{{wordModelList[index]}}</p>--><!--没有这行popup-picker的v-model视图不能更新-->
                       
                     <p class="item-right" v-show="value.wordModelList[index][0] && value.wordModelList[index][0] === item.word_name"><icon  type="success"></icon></p>
                     <p class="item-right" v-show="value.wordModelList[index][0] && value.wordModelList[index][0] !== item.word_name"><icon  type="warn"></icon></p>
                   </div>
                   <div class="item-row" v-show="value.wordModelList[index][0] && value.wordModelList[index][0] === item.word_name">
                      <div class="item-left">
                        <p style="margin:5px 0" class="color2 size3 ellipsis rest">英&nbsp;[{{item.ph_en}}] &nbsp;美&nbsp;[{{item.ph_am}}]</p>
                        <p  class="sentence sentence1 color3 size3">{{item.sentence.Network_en}}</p>
                        <p  class="sentence sentence2 color3 size3">{{item.sentence.Network_cn}}</p>
                    </div>
                   </div>
                   
                 </div>
                 <div class="type2" v-if="type === 2" @click="showPopupPicker(index,item.means)">
                   <div  class="item-row item-row1 box-start">
                     <p class="num color3 ">{{item.num}}.</p>
                     <p class="color1 en-size1">{{item.word_name}}</p>
                   </div>
                   <div  class="box-start item-row item-row2" >
                       <div class="color2 size2 item-left" >
                          <p class="color2 size3 ellipsis rest">英&nbsp;[{{item.ph_en}}] &nbsp;美&nbsp;[{{item.ph_am}}]</p>
                          <div class="box-justify">
                            <p class="color3 size3" v-show="!value.meanModelList[index][0]" >点击选择</p>
                            <p class="color2 size3">{{value.meanModelList[index][0]}}</p>
                          </div>
                       </div>
                       <!--<p style="display:none">{{meanModelList[index]}}</p>--><!--没有这行popup-picker的v-model视图不能更新-->
                       
                     <p class="item-right" v-show="value.meanModelList[index][0] && value.meanModelList[index][0] === item.means"><icon  type="success"></icon></p>
                     <p class="item-right" v-show="value.meanModelList[index][0] && value.meanModelList[index][0] !== item.means"><icon  type="warn"></icon></p>
                   </div>
                   <div class="item-row" v-show="value.meanModelList[index][0] && value.meanModelList[index][0] === item.means">
                     <div class="item-left" style="margin-top:5px">
                      <p  class="sentence sentence1 color3 size3">{{item.sentence.Network_en}}</p>
                      <p  class="sentence sentence2 color3 size3">{{item.sentence.Network_cn}}</p>
                     </div>
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
      
   
    <div id="right" >
      <div class="inner-right-wrapper box-v-justify">
        <div class="inner-empty"></div>
        <div class="inner-right box-v-start rest">
          <div v-show="searchInputValue === ''" class="size3 icon-wrapper" @click="searchAll">
            <x-icon  style="fill:#F70968;" type="ios-heart" size="38" ></x-icon>
            <div class="icon-name box-center" style="color:#fff">all</div>
          </div>
          <div v-show="searchInputValue !== ''" class="size3 icon-wrapper" @click="searchAll">
            <x-icon  style="fill:#F70968;" type="ios-heart-outline" size="38" ></x-icon>
            <div class="icon-name box-center" style="color:#F70968">all</div>
          </div>
          <div class="icon-wrapper size3" v-for="(item, index) in searchData" :key="index" @click="wordFilter(item)">
            <x-icon style="fill:#F70968;" v-show="searchInputValue!=='' && (searchInputValue === item)"  type="ios-heart" size="38" ></x-icon>
            <x-icon style="fill:#F70968;" v-show="searchInputValue !== item"  type="ios-heart-outline" size="38" ></x-icon>
            <div class="icon-name box-center" :class="searchInputValue && (searchInputValue === item)?'current-name':''">{{item}}</div>
          </div>
          
          
        </div>
      </div>
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
    
    console.log("that.searchData",that.searchData)
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
              wordObj.means = wordObj.symbols[0]["parts"][0].part + wordObj.symbols[0]["parts"][0].means.join(";")
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
        that.searchData = that.group[that.list1[that.swiperIndex]];
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
