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
    <div class="rest" style="overflow:auto;" @click="stopPlay">
      
        <swiper v-if="activeKey && list2[activeKey] && list2[activeKey].list" v-model="swiperIndex" height="100%" :show-dots="false" @on-index-change="changeSwiper" :auto="autoPlay" :loop="loop" :interval="interval" >
          <!--<scroller class="scroll-wrapper" :on-refresh="refresh"
               :on-infinite="infiniteHandler"
               style="padding-top: 44px;">-->
          <swiper-item  v-for="(item, index) in list2[activeKey].list" :key="index" :selected="index===swiperIndex">
            <!--<div v-show="outerIndex !== swiperIndex" style="height:100%"><loading :show="showLoading" text="Loading"></loading></div>-->
            <div style="height:100%;overflow:hidden"> 
              <div class="tab-swiper box-v-center align-stretch" style="height:100%;">
                
                <div  class="item-row item-row1" style="padding:10px">
                  <div class="size1 box-center">
                      <p class="num color3 box-end">{{item.num}}.</p>
                      <p class="color1 big-size1">{{item.word_name}}</p>
                      <p class="color3 size3" @click="addToCategory(item)" style="margin-left:20px;font-size:20px">+</p>
                      
                  </div>
                  <p class="color2 size2 ellipsis box-start item-row">英&nbsp;[{{item.ph_en}}] &nbsp;美&nbsp;[{{item.ph_am}}]</p>
                  <p class=" color2 size1 box-start item-row" v-html="item.means"></p>
                  <div class=" color2 size2 box-justify item-row">
                    <p>
                      <span class="color3 size3" style="margin-right:5px">我的备注：</span> 
                      
                    </p>
                    <p class=" color3 size3 box-end item-row" style="width:50px" @click="editRemark(item.word_name)">修改</p>
                  </div>
                  <p style="word-break: break-all;" class="color2 size1">{{remark[item.word_name]}}</p>
                  
                </div>
                <div class="rest" style="overflow:auto;padding:10px">
                  <template v-if="!item.moreSentence">
                    <p  class="sentence sentence1 color2 size1 box-start">{{item.sentence.Network_en}}</p>
                    <p  class="sentence sentence2 color3 size2 box-start">{{item.sentence.Network_cn}}</p>
                    <p class="box-center color3 size3" @click="getMoreSentence(item)">more</p>
                  </template>
                  <div v-if="item.moreSentence" style="overflow:auto;">
                    <div v-for="(sentenceItem,sentenceIndex) in item.moreSentence" :key="sentenceIndex" class="sentence sentence2 color3 size3 box-v-center align-start" style="padding:5px 0">
                      <p class="sentence sentence1 color2 size1 box-start">{{sentenceItem.Network_en}}</p>
                      <p class="sentence sentence2 color3 size2 box-start">{{sentenceItem.Network_cn}}</p>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
    </div>
    <div style="padding:10px">
      <group>
          <x-switch v-show="isShowSwitch"  title="自动播放" v-model="autoPlay"></x-switch>
        </group>
    </div>
    <confirm v-model="isShowRemarkConfirm"
    ref="confirm2"
    :title="remarkConfirmTitle"
    @on-confirm="saveRemark">
      <group><textarea style="border:none;width:100%;height:100px;font-size:16px;padding:5px;box-sizing:border-box;line-height:22px" placeholder="备注" v-model="remark[currentWord]"></textarea></group>
      <p style="margin-top:10px" class="color3 size3">注：使用备注请关闭无痕浏览模式，备注只保存在本地，清除浏览器数据后备注也会被清除</p>
    </confirm>  
  </div>
</template>
<script>
import Vue from 'vue'
import axios from 'axios';
//import config from '../js/cet4/config.js';
//console.log("config",config)

import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem, Datetime, Selector, Marquee, MarqueeItem, Toast , PopupPicker , ButtonTab, ButtonTabItem,Icon ,Loading,Popover,XInput,Confirm,TransferDomDirective as TransferDom, ConfirmPlugin ,XSwitch} from 'vux'
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
    Confirm,
    XSwitch
  },
  data () {
    return {
      
      haveWatchAutoPlay:false,
      interval: 6000,
      loop: false,
      isShowSwitch: false,
      autoPlay: true,//如果默认false,切换成true时，不能自动播放，为防止闪烁，初始化时通过isShowSwitch控制switch的显示
      currentWord:'',
      isShowRemarkConfirm: false,
      remarkConfirmTitle: '',
      remark:{},
      currentLabel:{},
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
      readonly : false,
               
      /*english : {
        wordList : [[]],
        meanList : [[]],
        wordModelList : []
      },
      popupValue : [""]*/

    }
  },
  watch : {
    /*autoPlay: function(val,oldVal) {//初始值autoPlay=false,改变值时，不会播放，加一个watch,只watch一次
      var that = this
      console.log(val)
      if(that.haveWatchAutoPlay || !that.isWatchAutoPlay){
        return
      }
      var temp = Object.assign([],that.list2[that.activeKey])
      that.list2[that.activeKey] = []
      //debugger
      setTimeout(function(){
        that.list2[that.activeKey] = temp
       // that.swiperIndex++
        that.haveWatchAutoPlay = true
      },10)
      
      //
    }*/
  },
  filters : {
    
  },
  created(){
    console.log("type",this.type)
    var that = this;
    var name = that.$router.history.current.params.name;
    const api = 'static/cet4/cet4-'+name+'.js';
    var myCategory = localStorage.getItem('myCategory')
    var remark = localStorage.getItem('remark')
    try {
      myCategory = JSON.parse(myCategory)
      that.remark = JSON.parse(remark) || {}
      console.log(that.remark,"that.remark")
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
        
        setTimeout(function(){
          that.isShowSwitch = true
          that.autoPlay = false
        },100)
        that.$vux.loading.hide()

      })
    
    
  },
  methods: {
      setAutoPlay: function() {//初始值autoPlay=false,改变值时，不会播放，加一个watch,只watch一次
        var that = this
        if(that.haveWatchAutoPlay){
          return
        }
        var temp = Object.assign([],that.list2[that.activeKey])
        that.list2[that.activeKey] = []
        //debugger
        setTimeout(function(){
          that.list2[that.activeKey] = temp
         // that.swiperIndex++
          that.haveWatchAutoPlay = true
        },10)
      },
      stopPlay: function() {//这里有一个bug,autoPlay为false时，swiper区域任何地方点一下都会触发自动滚动，因此在swiper父元素上加了这个事件
        var that = this
        if(!that.autoPlay) {
          that.autoPlay = "bug"//一定要先设置一个值再重置为false,不要设置成true,不然switch按钮会闪烁
          setTimeout(function(){
            that.autoPlay = false
          },10)
        }
        
      },
      editRemark: function(wordName){
        var that = this
        that.isShowRemarkConfirm = true

        that.currentWord = wordName
      },
      saveRemark: function(){
        var that = this
        localStorage.setItem("remark",JSON.stringify(that.remark))
      },
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
        that.swiperIndex = that.currentLabel[that.activeKey] || 0
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
       
      addToCategory: function(item) {
        var that = this
        that.isShowConfirm = true
        that.activeWord = item
      },
      
      getKey:function(key){
        var that = this;
        key = key.toLowerCase();
        that.searchInputValue += key;
      },
     
      
     
      changeSwiper : function(index){
        console.log(index)

        var that = this;
        console.log(that.autoPlay)
        if(!that.autoPlay) {
          that.autoPlay = "bug"//一定要先设置一个值再重置为false,不要设置成true,不然switch按钮会闪烁
          setTimeout(function(){
            that.autoPlay = false
          },10)
        }
        
        that.swiperIndex = index;
        that.currentLabel[that.activeKey] = that.swiperIndex
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
.big-size1{
  font-size:28px;
}
.big-size2{
  font-size:24px;
}
.big-size3{
  font-size:20px;
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
  .item-row {
    padding:5px 0;
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
  .sentence{
    line-height:24px;
  }
  textarea:focus{
    outline:none;
  }
</style>
