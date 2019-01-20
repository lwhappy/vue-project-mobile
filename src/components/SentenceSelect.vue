<template>
  <div class="index-container">
    <h1><strong>胖番茄网</strong> -- <em>英语四级单词训练</em></h1>
    
    <div class="index-main">
      <ul>
        <li @click="go(item)" class="item" v-for="(item,index) in 15">{{item}}</li>
      </ul>
        
    </div>
  </div>
</template>



<script>
import Vue from 'vue'

//import { Tab, TabItem,Swiper, SwiperItem,Loading,XButton } from 'vux'  

export default {
  components: {
   
    
  },
  data () {
    return {
       
    }
  },
  created(){
    var that = this;
  },
  methods: {
    go : function(value){
      var that = this;
      var name = that.$router.history.current.params.name;
      that.$router.push({ name: 'sentence', params: { name:name,num:value}})
      
    },
  }
}
</script>
<style lang="less">
  html,body,#app,.index-container{
    height:100%;
  }
  body{
    background-color:#fff;
  }
  body .vux-tab .vux-tab-item{
    font-size:16px;
  }
  body .vux-slider{
    height:100%;
  }
  html body .weui-btn_primary {
      background-color: #1AAD19;
      color:#fff;
      padding:5px 20px;
  }
</style>
<style lang="less" scoped>
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/center.less';
@import '../css/common.css';
.index-container .index-main{
  padding:0 10px;
  height:350px;
}
h1{
  font-size:18px;
  padding:10px;
  text-align:center;
}
h1 strong{
  color:#EB2F2F;

}
h1 em{
    font-style: normal;
    color:rgb(82, 163, 227);
  }
.item-wrapper{
  width:100%;
}

.item{
  margin:10px;
  float:left;
  width:15%;
  height:auto!important;
  font-size:20px;
  color:#3A3A3A;
  border:solid 1px #52A3E3;
  border-radius:10px;
  text-align:center;
}
 
</style>
