<template>
  <div>
    <group>
      <x-switch :title="title" v-model="show"></x-switch>
    </group>
    <div v-transfer-dom>
      <alert v-model="show" :title="title" @on-show="onShow" @on-hide="onHide"> Your Message is sent successfully~</alert>
    </div>

    <group title="Prop: content">
      <x-switch :title="title" v-model="show2"></x-switch>
    </group>
    <div v-transfer-dom>
      <alert v-model="show2" :title="title" :content="title"></alert>
    </div>

    <group :title="title">
      <cell :title="title" @click.native="showPlugin" is-link></cell>
      <cell :title="title" @click.native="showPluginAuto" is-link></cell>
    </group>

    <group :title="title">
      <cell :title="title" @click.native="showModule" is-link></cell>
      <cell :title="title" @click.native="showModuleAuto" is-link></cell>
    </group>
  </div>
</template>



<script>
import { AlertModule, Alert, Group, XSwitch, Cell, TransferDomDirective as TransferDom } from 'vux'

export default {
  directives: {
    TransferDom
  },
  components: {
    Alert,
    Group,
    XSwitch,
    Cell
  },
  data () {
    return {
      title:"标题",
      show: false,
      show1: false,
      show2: false
    }
  },
  methods: {
    onHide () {
      console.log('on hide')
    },
    onShow () {
      console.log('on show')
    },
    showPlugin () {
      this.$vux.alert.show({
        title: 'VUX is Cool',
        content: 'Do you agree?',
        onShow () {
          console.log('Plugin: I\'m showing')
        },
        onHide () {
          console.log('Plugin: I\'m hiding now')
        }
      })
    },
    showModule () {
      AlertModule.show({
        title: 'VUX is Cool',
        content: 'Do you agree?',
        onShow () {
          console.log('Module: I\'m showing')
        },
        onHide () {
          console.log('Module: I\'m hiding now')
        }
      })
    },
    showModuleAuto () {
      this.showModule()
      setTimeout(() => {
        AlertModule.hide()
      }, 3000)
    },
    showPluginAuto () {
      this.showPlugin()
      setTimeout(() => {
        this.$vux.alert.hide()
      }, 3000)
    }
  },
  mounted () {
    this.timer = setInterval(() => {
      console.log(this.$vux.alert.isVisible())
    }, 1000)
  },
  beforeDestroy () {
    clearInterval(this.timer)
  }
}
</script>
