<template>
  <div style="padding-bottom:44px">
    <div>
      <div style="padding:5px 10px" v-for="(item , index) in myCategory" :key="item.createTime" class="vux-1px-b">
        <div class="box-justify" >
          <div class="size1 color1 rest box-justify" style="margin-right:10px" @click="switchShow(item,index)">
            <p class="rest ellipsis">{{item.name}}</p>
            <div class="color3 box-center" style="font-size:20px;width:20px">
              <p v-show="item.isShow">-</p>
              <p v-show="!item.isShow">+</p>
            </div>
          </div>
          <p class="size3 color3" @click="deleteCategory(index,item)"  >delete</p>
        </div>
        <div style="margin:0 10px" v-show="item.isShow" >
          <div v-for="(word,wordIndex) in item.word" :key="word.word_name" :class="wordIndex===(item.word.length-1)?'':'vux-1px-b'" style="padding:5px 0">
            <p class="size2 color2">{{word.word_name}}</p>
            <p class="size2 color2">[{{word.ph_en}}]&nbsp;&nbsp;[{{word.ph_am}}]</p>
            <p class="size2 color2">{{word.means}}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="bot">
      <div class="box-center">
        <x-icon @click="showConfirm" size="50" style="fill:#F70968;" type="ios-plus-outline" ></x-icon>
      </div>
    </div>
    <div v-transfer-dom>
      <confirm v-model="isShowConfirm"
      show-input
      ref="confirm1"
      :title="confirmTitle"
      @on-cancel="onCancel"
      @on-confirm="onConfirm"
      @on-show="onShow"
      @on-hide="onHide">
      </confirm>
    </div>
  </div>
</template>



<script>
import Vue from 'vue'
import axios from 'axios';
//import config from '../js/cet4/config.js';
//console.log("config",config)

import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem, Datetime, Selector, Marquee, MarqueeItem, Toast , PopupPicker , ButtonTab, ButtonTabItem,Icon ,Loading,Popover,XInput, Confirm, TransferDomDirective as TransferDom, ConfirmPlugin  } from 'vux'
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
      confirmTitle: '添加分类',
      isShowConfirm: false,
      myCategory: []
    }
  },
  watch : {
    
  },
  filters : {
    
  },
  created(){
    var that = this
    var myCategory = localStorage.getItem('myCategory')
    try {
      myCategory = JSON.parse(myCategory)
    } catch(e){
      console.log(e)
    }
    if(myCategory instanceof Array){
      that.myCategory = myCategory
    }
    /*for(var i=0,len=that.myCategory.length;i<len;i++){
      that.myCategory[i].isShow = true
    }*/
    
  },
  methods: {
   switchShow: function(item,index){
      var that = this
      item.isShow = !item.isShow
      Vue.set(that.myCategory, index, item)
   },
   deleteCategory: function(index,item){
      var that = this
      this.$vux.confirm.show({
        title: '确定要删除吗？',
        content: item.name,
        onCancel () {

        },
        onConfirm () {
          that.myCategory.splice(index , 1)
          var myCategory = JSON.stringify(that.myCategory)
          localStorage.setItem('myCategory',myCategory)
        }
      })
   },
   onCancel: function(){

   },
   onConfirm: function(name){
      var that = this
      this.$refs.confirm1.setInputValue('')
      //this.$vux.toast.text('input value: ' + value)

      if(name){
        for(var i=0,len=that.myCategory.length;i<len;i++){
          if(that.myCategory[i].name === name){
            that.$vux.toast.text('有重名')
            return
          }
        }
        var obj = {
          createTime: new Date().getTime().toString(),
          name: name,
          word: []
        }
        that.myCategory.push(obj)
        var myCategory = JSON.stringify(that.myCategory)
        localStorage.setItem('myCategory',myCategory)
      }
   },
   onHide: function(){

   },
   onShow: function(){
      this.$refs.confirm1.setInputValue('')
   },  
   showConfirm: function(){
      var that = this
      that.isShowConfirm = true
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

  
</style>
<style lang="less" scoped>
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/center.less';
@import '../css/common.css';

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
  .bot{
    position:fixed;
    left:0;
    bottom:0;
    width:100%;
    height:44px;
  }
</style>
