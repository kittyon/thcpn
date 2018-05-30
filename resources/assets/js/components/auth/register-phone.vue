<template>
  <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="register-phone-form" label-position="top">
    <h2 class="heading">注册用户</h2>
    <el-form-item prop="phone">
      <el-input type="text" v-model="model.phone" placeholder="请输入手机号"/>
    </el-form-item>
    <el-button type="primary" :loading="loading" @click="submit('register-phone-form')">{{ loading ? '加载...' : '下一步' }}</el-button>
  </el-form>
</template>
<script>
export default{
  data(){
    const rules = {
      phone: [
        { required: true, message: '请输入手机号', trigger: 'blur' },
        { pattern: /^1[34578]\d{9}$/, message: '目前只支持中国大陆的手机号码' }
      ]
    }
    return { next:2, model: {phone:""}, rules: rules, error: null, loading: false }
  },
  methods:{
    submit(ref){
      this.$ref[ref].validate(valid => {
        if (!valid) return false
        // validated
        this.error = null
        this.loading = true
        this.$emit("actived",this.next);
        this.$route.push({ name: "register-captcha", params: this.model);
      });

    },

  }
}
</script>
<style>
</style>
