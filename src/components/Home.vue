<template>
  <div class="tab-container box-v-start align-stretch">
    <h1>个人资产管理</h1>
    <div class="tab-header">
      <tab :line-width=2 active-color='#fc378c' v-model="index">
        <tab-item class="vux-center" :selected="demo2 === item" v-for="(item, index) in list1" @click="demo2 = item" :key="index">{{item}}</tab-item>
      </tab>
    </div>  
    <div class="rest">
      <swiper v-model="index" height="100%" :show-dots="false">
        <swiper-item v-for="n in list1.length" :key="n" :selected="n===1">
          <div class="box-justify item-key">
            <p class="field">电脑名</p>
            <p class="field">所属人</p>
            <p class="field field-date">录入时间</p>
            <p class="field field-status">状态</p>
          </div>
          <div v-for="(item, index) in list2" :key="index" class="tab-swiper  bd-bottom">
            <div class="box-justify item-row" @click="operate(item,index)">
              <p class="field">{{item.name}}</p>
              <p class="field">{{item.userName}}</p>
              <div class="field field-date" @click.stop>
                <datetime

                  v-model="item.addTime"
                  @on-change="dateChange"
                  @on-cancel="dateCancel"
                  @on-confirm="dateConfirm"
                  @on-hide="dateHide"></datetime>
              </div>
              <div class="field field-status" @click.stop>
                <selector placeholder="" v-model="item.status" title="" name="district" :options="selectList" @on-change=""></selector>
              </div>
            </div>
            <div class="item-operate" v-show="item.isShow">
              <div class="box-justify operate-row">
                <p class="operate-btn" @click="top(index)">置顶</p>
                <p class="operate-btn" @click="deleteItem(index)">删除</p>
              </div>
            </div>
          </div>
        </swiper-item>
      </swiper>
    </div>
    <div class="notice box-start">
      <p class="notice-label">公告：</p>
      <marquee :interval="3000">
        <marquee-item v-for="i in asyncCount" :key="i" @click.native="" class="align-middle">hello world {{i}}</marquee-item>
      </marquee>
    </div>
  </div>
</template>



<script>
import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem, Datetime, Selector, Marquee, MarqueeItem, Toast } from 'vux'
const list1 = () => [
      '深圳公司', 
      '广州公司', 
      '香港公司', 
      '台湾公司', 
      '美国公司'
  ]
  const list2 = () => [
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "1",isShow : true}, 
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "1",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "0",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "0",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "1",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "0",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "1",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "0",isShow : false},
        {name : "戴尔001",  userName:"小王"  ,addTime : "2017-06-01",status : "1",isShow : false}, 
        
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
    Toast
  },
  data () {
    return {
      index01: 0,
      list1: list1(),
      list2: list2(),
      index: 0,
      demo2: '标题2',
      selectList: [{key: '0', value: '停用'}, {key: '1', value: '使用中'}],
      selectStatus : '1',
      asyncCount : 5,

    }
  },
  methods: {
    top(){

    },
    dateConfirm(){

    },
    dateCancel(){

    },
    dateChange(){

    },
    dateHide(){

    },
    operate (item,index){
      for(var i=0,len=this.list2.length;i<len;i++){
        if(index === i){
          item.isShow = !item.isShow;
        }
        else{
          this.list2[i].isShow = false;
        }
        
      }
      
    },
    deleteItem(index){
      var that = this;
      this.$vux.confirm.show({
        title: '提示',
        content: '确定删除吗？',
        onShow () {
          console.log('plugin show')
        },
        onHide () {
          console.log('plugin hide')
        },
        onCancel () {
          console.log('plugin cancel')
        },
        onConfirm () {
          console.log('plugin confirm')
          that.list2.splice(index,1)
          that.$vux.toast.show({
            text: '删除成功',
            onShow () {
              console.log('Plugin: I\'m showing')
            },
            onHide () {
              console.log('Plugin: I\'m hiding')
            }
          })
        }
      })
      
    },
    switchTabItem (index) {
      console.log('on-before-index-change', index)
      this.$vux.loading.show({
        text: 'loading'
      })
      setTimeout(() => {
        this.$vux.loading.hide()
        this.index01 = index
      }, 1000)
    },
    onItemClick (index) {
      console.log('on item click:', index)
    },
    addTab () {
      if (this.list2.length < 5) {
        this.list2 = list().slice(0, this.list2.length + 1)
      }
    },
    removeTab () {
      if (this.list2.length > 1) {
        this.list2 = list().slice(0, this.list2.length - 1)
      }
    },
    next () {
      if (this.index === this.list2.length - 1) {
        this.index = 0
      } else {
        ++this.index
      }
    },
    prev () {
      if (this.index === 0) {
        this.index = this.list2.length - 1
      } else {
        --this.index
      }
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
  h1{
    height:30px;
    font-size:16px;
    text-align:center;
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
    height:50px;
    padding:0 10px;
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
</style>
