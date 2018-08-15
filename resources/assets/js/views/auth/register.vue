<template>
  <section class="register">
    <header class="register-header">
      <h1 class="brand"><router-link to="/" tabindex="-1">THC</router-link></h1>
      <el-alert v-if="error" :title="error.title" type="warning" :description="error.message" show-icon/>
    </header>
    <el-steps :active="active" finish-status="success">
      <el-step :title="$t('auth.step')+'1'"></el-step>
      <el-step :title="$t('auth.step')+'2'"></el-step>
      <el-step :title="$t('auth.step')+'3'"></el-step>
    </el-steps>
    <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="register-form" label-position="left">
      <h2 class="heading">{{$t('auth.register')}}</h2>
      <el-form-item prop="phone" v-if="this.active==0">
        <el-input type="text" v-model="model.phone" :placeholder="$t('auth.phonePlaceholder')"/>
      </el-form-item>
      <div class="captcha-container">
        <el-form-item prop="captcha_code" v-if="this.active==1">
            <el-row>
              <el-col :span="12">
                <el-input v-model="model.captcha_code" :placeholder="$t('auth.captchaCode')">
                </el-input>
              </el-col>
              <el-col :span="8">
                <img  :title="$t('auth.captchaTitle')" v-bind:src="captcha_image_content_ed" @click="get_captcha(()=>{this.acitve = 0;this.acitve = 1;},()=>{})"/>
              </el-col>
            </el-row>
        </el-form-item>
      </div>
      <div v-if="this.active==2">
        <el-form-item prop="verification_code">
          <el-input v-model="model.verification_code" :placeholder="$t('auth.captchaCode')">

          </el-input>
          <el-button slot="append">{{$t('auth.captchaGot')}}</el-button>
        </el-form-item>
        <el-form-item prop="name">
          <el-input v-model="model.name" :placeholder="$t('auth.usernamePlaceholder')"/>
        </el-form-item>
        <el-form-item prop="email">
          <el-input type="email" v-model="model.email" :placeholder="$t('auth.emailPlaceholder')"/>
        </el-form-item>
        <el-form-item prop="password">
          <el-input type="password" v-model="model.password" :placeholder="$t('auth.passwordPlaceholder')"/>
        </el-form-item>
      </div>
      <el-form-item>
        <el-button @click="last" v-if="active!=0" icon="el-icon-arrow-left">{{$t('auth.back')}}</el-button>
        <el-button type="primary" :loading="loading" @click="submit('register-form')" icon="el-icon-arrow-right" style="float: right">{{ active==2 ? $t('auth.register') : $t('auth.next') }}</el-button>
      </el-form-item>
    </el-form>
    <footer class="login-footer clearfix">
      ← {{$t('auth.backTo')}}<a href="/">THCreate</a>
    </footer>
  </section>
</template>
<script>
export default {
  name: 'register',
  title: 'Register « THC | make IT better',
  data () {
    // form model
    // TODO: remove default values
    var model = {
      phone:'',
      captcha_code:"",
      captcha_key:"",
      verification_key: "",
      verification_code : "",
      name : "",
      email : "",
      password : "",
    }
    // form validate rules
    const rules = {
      phone: [
        { required: true, message: '请输入手机号', trigger:'blur' },
        { min: 2, max: 16, message: '长度在 2 到 16 个字符' }
      ],
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
      ],
      captcha_code: [
        { required: true, message: '请输入验证码', trigger: 'blur' },

      ]

    }
    return { model: model, rules: rules, error: null, loading: false, active: 0, captcha_image_content:"" }
  },
  computed:{
    captcha_image_content_ed: function(){
      return this.captcha_image_content;
    }
  },
  methods: {
    next(){
      this.active+=1;
    },
    last(){
      this.active-=1;
    },
    get_captcha(successed, failed){
      self = this;
      let params = {phone: this.model.phone}
      axios.post("captchas",params).then(function(res){
        self.captcha_image_content = res.data.captcha_image_content;
        self.model.captcha_key = res.data.captcha_key;
        successed();
      }).catch(err => {
        console.log(err)
        self.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = '验证码获取错误,请重试！'
            break
          case 500:
            self.error.message = '服务器内部异常，请稍后再试！'
            break
          default:
            this.error.message = err.response.message;
            break
        }
        failed();
      });
    },
    submit_phone(){
      self = this;

      this.get_captcha(()=>{self.next()},()=>{});

    },
    submit_captcha(){
      let params = {
        captcha_code:this.model.captcha_code,
        captcha_key:this.model.captcha_key
      };
      self = this;
      axios.post('verificationCodes', params).then(function(res){
        self.model.verification_key = res.data.key || "";
        self.next();
      }).catch(err => {
        self.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = '验证码错误！'
            break
          case 500:
            self.error.message = '服务器内部异常，请稍后再试！'
            break
          case 422:
            self.error.message = "验证码失效"
            break
          default:
            this.error.message = err.response.message;
            break
        }
      });

    },
    submit_verifyCode(){
      let params = {
        verification_key: this.model.verification_key,
        verification_code : this.model.verification_code,
        name : this.model.name,
        email : this.model.email,
        password : this.model.password,
      };
      self = this;
      axios.post("users", params).then(res=>{
        self.$store.commit("login",res.data);
        self.$router.replace({name: "main"});
      })
      .catch(err => {
        console.log(err)
        self.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = '验证码错误！'
            break
          case 500:
            self.error.message = '服务器内部异常，请稍后再试！'
            break
          default:
            self.error.message = err.response.message;
            break
        }
      });
    },
    submit(ref){
      this.$refs[ref].validate(valid => {
        if (!valid) return false
        // validated
        this.error = null
        this.loading = true

        switch(this.active){
          case 0:
            this.submit_phone();
            break
          case 1:
            this.submit_captcha();
            break;
          case 2:
            this.submit_verifyCode();
            break
          default:
            this.active = 0;
        }
        this.loading = false
      });

    },
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  @import '../../styles/variables';
  .register {
    flex: 1;
    width: 95%;
    max-width: 22rem;
    margin: 0 auto;
    font-size: .875rem;
    &-header {
      margin-bottom: 1rem;
      .brand {
        margin: 4.5rem 0 3.5rem;
        text-align: center;
        letter-spacing: .125rem;
        a {
          margin: 0;
          color: $brand-color;
          font: 300 3rem sans-serif;
          &:hover {
            color: $brand-hover-color;
            text-shadow: 0 0 1rem $brand-hover-color;
          }
        }
      }
    }
    &-form {
      margin-bottom: 2.5rem;
      padding: 1.875rem 1.25rem;
      background: $login-form-background;
      color: $login-form-color;
      .heading {
        margin: 0 0 1rem;
        font-weight: 400;
        font-size: 1.5rem;
      }
      .el-button {
        margin-top: .5rem;
      }
    }
    &-footer {
      margin-bottom: 1rem;
      padding: .625rem;
      border: .0625rem solid $brand-color;
      font-size: .75rem;
      text-align: center;
      a {
        color: $brand-color;
      }
    }
  }

</style>
