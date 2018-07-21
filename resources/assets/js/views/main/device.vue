<template>
  <div>
    <dev-header></dev-header>
    <router-view></router-view>
  </div>
</template>

<script>
import DHeader from "./dev-header.vue"
export default {
  name: 'device',
  components:{
    "dev-header": DHeader
  },

}
</script>
