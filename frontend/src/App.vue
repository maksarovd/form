<template>
  <img alt="Vue logo" src="./assets/logo.png">

  <FormItem msg="My Custom Component"/>
  <InputItem />
</template>

<script>
import FormItem from './components/FormItem.vue'
import InputItem from './components/InputItem.vue'

export default {
  name: 'App',
  components: {
    FormItem,
    InputItem
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
