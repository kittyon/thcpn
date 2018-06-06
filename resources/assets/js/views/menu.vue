<template>
  <el-menu :default-active="activeIndex" mode="horizontal" @select="handleSelect">

  </el-menu>
</template>

<script>
export default {
  name: 'menu-list',
  props: {
    items: Array,
  },
  data(){
    return {
      activeIndex: '1'
    }
  },
  methods: {
    handleSelect(key, keyPath) {
        console.log(key, keyPath);
    }
  }
}
</script>
