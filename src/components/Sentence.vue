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
              <div style="" v-for="(item, index) in value.sentences" :key="index"  class="tab-swiper " :class="item.isActive?'vux-1px sentence-active':'vux-1px-b'" @click="setActive(key,index)">
                <div  class="type0 box-start" >
                  <div>
                    <div  class="item-row item-row1">
                      <div class="size1 box-start">
                          <p class="num color3 box-end">{{index+1}}.</p>
                          <p v-show="!item">{{value.list[index].word_name}}</p>
                          <p v-show="!isShowKeybord" class="color1 size1" v-html="wordColor(item.Network_en,value.list[index])"></p>
                          <p v-show="isShowKeybord" class="color1 size1">{{wordLeft(item.Network_en,value.list[index])}} <span class="sentence-word">({{value.list[index].model}})</span> {{wordRight(item.Network_en,value.list[index])}}</p>
                          
                      </div>
                    </div>
                    <div  class="item-row item-row2 ">
                      
                      <div class="item-left">
                        <p class="color2 size2" >{{item.Network_cn}}</p>
                        <p v-show="isTip && item.isActive" class="color2 size3 ellipsis rest">英&nbsp;[{{value.list[index].ph_en}}] &nbsp;美&nbsp;[{{value.list[index].ph_am}}]</p>
                        <p v-show="isTip && item.isActive" class=" color2 size3 ">{{value.list[index].means}}</p>

                        <!--<p @click="showSentence(item)" class="ellipsis color2 size2">例句</p>
                        <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_en}}</p>
                        <p v-show="item.showSentence" class="ellipsis color2 size2">{{item.sentence.Network_cn}}</p>-->

                      </div>
                    </div>
                  </div>

                  <p class="item-right" v-show="value.list[index].model === value.list[index].word_name"><icon  type="success"></icon></p>
                  <p class="item-right" v-show="value.list[index].model !=='?' && value.list[index].model !== value.list[index].word_name"><icon  type="warn"></icon></p>
                </div>
                 
               
               
              </div>
            </div>
          </swiper-item>
          <!--</scroller>-->
        </swiper>
      
    </div>
   
    <popup-picker style="display:none"  class="size2 color2" :show="isShowPopupPicker" title="点击选择" :data="popupData" @on-hide="hidePopup()" @on-show="showPopup()" @on-change="changeWordPopup()" v-model="popupValue" :popup-title="popupTitle">
      
    </popup-picker><!--只初始化一个-->
    <div class="bot ">
      <div class="bot-inner box-v-start">
        <div v-show="!isShowKeybord" class="box-v-start algin-center" style="margin-bottom:-10px;" @click="isShowKeybord=true">
          <x-icon style="margin-bottom:-20px;fill:red" type="ios-arrow-up" size="40"></x-icon>
          <x-icon type="ios-arrow-up" style="fill:red" size="40"></x-icon>
        </div>
        <div v-show="isShowKeybord" class="keybord vux-1px-t" >
          <div class="box-center keybord-item">
            <p @click="getKey('a')">a</p>
            <p @click="getKey('b')">b</p>
            <p @click="getKey('c')">c</p>
            <p @click="getKey('d')">d</p>
            <p @click="getKey('e')">e</p>
          </div>
          <div class="box-center keybord-item">
            <p @click="getKey('f')">f</p>
            <p @click="getKey('g')">g</p>
            <p @click="getKey('h')">h</p>
            <p @click="getKey('i')">i</p>
            <p @click="getKey('j')">j</p>
          </div>
          <div class="box-center keybord-item">
            <p @click="getKey('k')">k</p>
            <p @click="getKey('l')">l</p>
            <p @click="getKey('m')">m</p>
            <p @click="getKey('n')">n</p>
            <p @click="getKey('o')">o</p>
          </div>
          <div class="box-center keybord-item">
            <p @click="getKey('p')">p</p>
            <p @click="getKey('q')">q</p>
            <p @click="getKey('r')">r</p>
            <p @click="getKey('s')">s</p>
            <p @click="getKey('t')">t</p>
          </div>
          <div class="box-center keybord-item">
            <p @click="getKey('u')">u</p>
            <p @click="getKey('v')">v</p>
            <p @click="getKey('w')">w</p>
            <p @click="getKey('x')">x</p>
            <p @click="getKey('y')">y</p>
          </div>
          <div class="box-center keybord-item">
            <p @click="getKey('z')">z</p>
            <p class="chinese" @click="isTip = true">提示</p>
            <p class="chinese" @click="getAnswer">答案</p>
            <p style="margin-bottom:-10px"><x-icon style="fill:red;" type="ios-arrow-thin-left" size="30" @click="backspace"></x-icon></p>
            <p style="margin-bottom:-10px"><x-icon style="fill:red;" type="ios-close-outline" size="30" @click="isShowKeybord=false"></x-icon></p>
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
      sentenceApi : location.hostname==='localhost'?'http://localhost/word/php/get-sentence.php':'/word/php/get-sentence.php',
      isTip: false,
      currentListItem: null,
      isShowKeybord:false,
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
              wordObj.model = "?"
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
            url:that.sentenceApi,
            method: 'post',
            data: param,
            headers:{
            }
            
          })
          .then(response=>{
            console.log(response.data)
            that.list2[firstKey].sentences = response.data
            that.$vux.loading.hide()
          })
        
        

      })
    
    
  },
  methods: {
      getAnswer: function(){
        var that  = this
        that.currentListItem.model = that.currentListItem.word_name
      },
      backspace:function(){
        var that = this
        if(that.currentListItem.model === "?"){
          return
        }
        var len = that.currentListItem.model.length
        if(len < 1){
          return
        }
        that.currentListItem.model = that.currentListItem.model.substr(0,len-1)
      },
      getKey: function(key){
        var that = this
        if(!that.currentListItem){
          this.$vux.toast.show({
           text: '请先点击选中一个句子',
           type: "text",
           width: '200px'
          })
          return
        }
        if(that.currentListItem.model === "?"){
          that.currentListItem.model = ''
        }
        that.currentListItem.model += key 
      },
      setActive: function(key,index){
        
        var that = this
        var obj = that.list2[key]
        var sentences = obj.sentences
        that.currentListItem = obj.list[index]
        for(var i=0,len=sentences.length;i<len;i++){
          var obj2 = sentences[i]
          if(!obj2){
            continue
          }
          obj2.isActive = false
          if(i === index){
            obj2.isActive = true
            //Vue.set(list, 0, newData)
          }
          Vue.set(sentences, i, obj2)
        }

        Vue.set(that.list2, key, obj)
      },
      wordLeft : function(a,obj) {
        var that = this
        if(obj){
          var b = obj.word_name
        }
        
        if(b){
          if(a && a.match(b) !== null){
            var index = a.indexOf(b);
            var str1 = a.substring(0,index );
            
            return str1;
          }
          else{
            return a;
          }
        }
        else {
          return a;
        }
      },
      wordRight : function(a,obj) {
        var that = this
        if(obj){
          var b = obj.word_name
        }
        
        if(b){
          if(a && a.match(b) !== null){
            var index = a.indexOf(b);
            var str2 = a.substring(index+b.length);
            return str2;
          }
          else{
            return a;
          }
        }
        else {
          return a;
        }
      },
      wordColor : function(a,obj) {
        var that = this
        if(obj){
          var b = obj.word_name
        }
        
        if(b){
          if(a && a.match(b) !== null){
            var index = a.indexOf(b);
            var str1 = a.substring(0,index );
            if(str1){
              str1 = "<span>" + str1 + "</span>";
            }
            var str2 = a.substring(index+b.length);
            if(str2){
              str2 = "<span>" + str2 + "</span>"
            }
            
              b = '<span class="sentence-word">' + b + '</span>';
            
            var word = str1 +b + str2;
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
        that.swiperIndex = index;
        var key = that.list1[index]
        that.currentListItem = null
        if(that.list2[key].sentences.length > 0){
          return
        }
        var list = that.list2[key].list
        var wordArr = []
        for(var i=0,len=list.length;i<len;i++){
          wordArr.push(list[i].word_name)
        }
        console.log(wordArr)
        
        //obj = JSON.stringify(obj)
        //wordArr = JSON.stringify(wordArr)
        that.$vux.loading.show({
          text: 'Loading'
        })
        let param = new URLSearchParams()
        param.append('words', JSON.stringify(wordArr))
        axios({
            url:that.sentenceApi,
            method: 'post',
            data: param,
            headers:{
            }
            
          })
          .then(response=>{
            console.log(response.data)
            var obj = that.list2[key]
            for(var i=0,len=response.data.length;i<len;i++){
              Vue.set(obj.sentences,i,response.data[i])
            }
            Vue.set(that.list2,key,obj)
            that.$vux.loading.hide()
          })
        
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
  .sentence-word{
    color:blue;
  }
  .sentence-active:before,.sentence-active:after{
    border-color:red!important;
  }
  .vux-1px:before{
    box-sizing:border-box!important;
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
  .item-row2{
    margin-top:5px;
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
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
  }
  .bot .keybord{
    background-color:#fff;
    width:100%;
  }
  .bot .keybord .keybord-item p{
    text-align:center;
    font-size:20px;
    padding:3px;
  }
  .bot .keybord .keybord-item p.chinese{
    font-size:14px;
  }

</style>
