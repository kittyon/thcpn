<template>
  <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="register-info-form" label-position="top">
    <h2 class="heading">信息资料</h2>
    <el-form-item prop="username">
      <el-input type="text" v-model="model.username" placeholder="请输入用户名"/>
    </el-form-item>
    <el-form-item prop="email">
      <el-input type="text" v-model="model.email" placeholder="请输入email"/>
    </el-form-item>
    <el-button type="primary" :loading="loading" @click="submit('register-info-form')">{{ loading ? '加载...' : '完成' }}</el-button>
  </el-form>
</template>
<script>
export default{

}
</script>
<style>
</style>
