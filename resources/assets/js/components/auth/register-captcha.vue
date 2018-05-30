<template>
  <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="register-capthca-form" label-position="top">
    <h2 class="heading">验证身份</h2>
    <el-form-item prop="captcha">
        <el-input v-model="model.captcha_code" placeholder="请输入验证码">
          <el-button slot="append" @click='getCaptcha'>{{ captchaMsg.captcha_image_content }}</el-button>
        </el-input>
    </el-form-item>
    <el-button type="primary" :loading="loading" @click="submit('register-capthca-form')">{{ loading ? '加载...' : '下一步' }}</el-button>
  </el-form>
</template>
<script>
export default{
  data(){
    const rules = {
      captcha: [
        { required: true, message: '请输入验证码', trigger: 'blur' },

      ]
    }
    return { captchaMsg:null, next:3, model: {captcha_code:"",captcha_key:""}, rules: rules, error: null, loading: false }
  },
  created:function(){
    this.getCaptcha();
  },
  methods:{
    getCaptcha(){
      let params = {phone: this.$route.params.phone}
      axios.post("captchas",params).then(function(res){
        this.captchaMsg = res;
        this.model.captcha_key = this.captchaMsg.captcha_key;
      });
    },
    submit(ref){
      this.$ref[ref].validate(valid => {
        if (!valid) return false
        // validated
        this.error = null
        this.loading = true

        axios.post('verificationCodes', this.model).then(function(res){
          this.$emit("actived",this.next);
          this.$route.push({ name: "register-verifyCode", params: res});
        });

      });

    },

  }

}
</script>
<style>
</style>
