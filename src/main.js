// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import FastClick from 'fastclick'
import VueRouter from 'vue-router'
import App from './App'
//import Home from './components/Home'
//import Tab from './components/Tab'
//import AlertDemo from './components/HelloFromVux'
import Word from './components/Word'
import WordIndex from './components/WordIndex'

Vue.use(VueRouter)

const routes = [
	{
  		path: '/',
  		component: WordIndex
	},
	/*{
  		path: '/tab',
  		component: Tab
	},
	{
  		path: '/alert',
  		component: AlertDemo
	},*/
  {
      path: '/word/:name/:type',
      component: Word,
      name :"word"
  },
  
  /*{
      path: '/w',
      component: WordIndex
  }*/
]

const router = new VueRouter({
  routes
})

FastClick.attach(document.body)

Vue.config.productionTip = false
import { Group, Cell, DatetimePlugin, CloseDialogsPlugin, ConfigPlugin, BusPlugin, LocalePlugin, DevicePlugin, ToastPlugin, AlertPlugin, ConfirmPlugin, LoadingPlugin, WechatPlugin, AjaxPlugin, AppPlugin } from 'vux'

Vue.component('group', Group)
Vue.component('cell', Cell)
// plugins
Vue.use(DevicePlugin)
Vue.use(ToastPlugin)
Vue.use(AlertPlugin)
Vue.use(ConfirmPlugin)
Vue.use(LoadingPlugin)
Vue.use(WechatPlugin)
Vue.use(AjaxPlugin)
Vue.use(BusPlugin)
Vue.use(DatetimePlugin)
/* eslint-disable no-new */
new Vue({
  router,
  render: h => h(App)
}).$mount('#app-box')
