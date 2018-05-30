<template>
  <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="register-verifyCode-form" label-position="top">
    <h2 class="heading">输入验证码</h2>
    <el-form-item prop="verification_code">
      <el-input v-model="model.verification_code" placeholder="请输入验证码">
        <el-button slot="append" @click='getCode' :disabled="hasGetCode">获取验证码</el-button>
        <i>{{count}}秒后重新获取</i>
      </el-input>
    </el-form-item>
    <el-form-item prop="name">
      <el-input v-model="model.name" placeholder="请输入用户名">
    </el-form-item>
    <el-form-item prop="email">
      <el-input type="email" v-model="model.email" placeholder="请输入email">
    </el-form-item>
    <el-form-item prop="password">
      <el-input type="password" v-model="model.password" placeholder="请输入密码">
    </el-form-item>
    <el-button type="primary" :loading="loading" @click="submit('register-verifyCode-form')">{{ loading ? '加载...' : '注册' }}</el-button>
  </el-form>
</template>
<script>
export default{
  data(){
    const model = {
      verification_key: this.$route.params.key,
      verification_code = "",
      name="",
      email="",
      password = "",
    };
    const rules = {
      name: [
        { required: true, message: '请输入用户名' },
        { min: 2, max: 16, message: '长度在 2 到 16 个字符' }
      ],
      password: [
        { required: true, message: '请输入密码' },
        { min: 6, max: 16, message: '长度在 6 到 16 个字符' }
      ],
      email:[
        {required: true, message: '请输入email'},
      ]
    }

    return { model:model, rules: rules, errors: null, loading: false, hasGetCode: true};
  },
  methods:{
    getCode(){

    },
    submit(ref){
      this.$ref[ref].validate(valid=>{
        if (!valid) return false
        this.error = null
        this.loading = true

        axios:post("users", this.model).then(res=>{
          this.$store.commit("login",res.data);
          this.$route.replace({name: "main"});
        })
        .catch(err => {
          console.error(err)
          this.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
          switch (err.response && err.response.status) {
            case 401:
              this.error.message = '验证码错误！'
              break
            case 500:
              this.error.message = '服务器内部异常，请稍后再试！'
              break
          }
        });
      });
    }

  }
}
</script>
<style>
</style>
