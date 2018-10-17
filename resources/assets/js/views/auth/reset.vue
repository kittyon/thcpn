<template>
<section class="reset">
  <header class="reset-header">
    <h1 class="brand"><router-link to="/" tabindex="-1">THC</router-link></h1>
    <el-alert v-if="error" :title="error.title" type="warning" :description="error.message" show-icon/>
  </header>
    <el-form class="reset-form" auto-complete="off" :model="model" :rules="rules" label-width="60px" ref="reset" label-position="right">
    <el-form-item :label="$t('auth.phone')" prop="phone">
      <el-input type="text" v-model="model.phone" :placeholder="$t('auth.phonePlaceholder')"/>
    </el-form-item>
    <el-form-item prop="captchaCode">
      <el-input type="text" class="captcha" v-model="model.resetPass_code" :placeholder="$t('auth.captchaCode')"/>
      <el-button :disabled="isCaptchaGetting" @click="getCaptcha()">{{captchaButtonTitle}}</el-button>
    </el-form-item>
    <el-form-item :label="$t('auth.password')" prop="password">
      <el-input type="password" v-model="model.password" :placeholder="$t('auth.resetPassword')"/>
    </el-form-item>
    <el-form-item :label="$t('auth.confirmPassword')" prop="confirmPassword">
      <el-input type="password" v-model="model.confirmpassword" :placeholder="$t('auth.resetConfirmPassword')"/>
    </el-form-item>
    <el-form-item>
      <el-button class="success" @click="resetPassword()">{{$t('auth.reset')}}</el-button>
      <el-button class="primary" @click="cancel()">{{$t('auth.cancel')}}</el-button>
    </el-form-item>
    </el-form>
  </section>
</template>
<script>
export default{
  data(){
    var validatePass = (rule, value, callback)=>{
      if (value === '') {
          callback(new Error(this.$t('error.password')));
        } else {
          if (this.model.confirmpassword !== '') {
            this.$refs.reset.validateField('confirmPassword');
          }
          callback();
        }
    };

    var validateConfirmPass = (rule, value, callback)=>{
      if (value === '') {
          callback(new Error(this.$t('error.confirmPassword')));
        } else if (value !== this.model.password) {
          callback(new Error(this.$t('error.diffrentPassword')));
        } else {
          callback();
        }
    };

    return {
      error:null,
      model:{
        phone: "",
        resetPass_key:"",
        resetPass_code: "",
        password: "",
        confirmpassword:""
      },
      rules:{
        phone:[
          { required: true, message: this.$t('error.phone'), trigger: 'blur' },
          { min: 2, max: 16, message: '长度在 2 到 16 个字符' }
        ],
        code:[
          {required: true, message: this.$t('error.captcha'), trigger: 'blur'},
        ],
        passowrd:[
          {validator: validatePass, trigger: 'blur'}
        ],
        confirmPassword:[
          {validator: validateConfirmPass, trigger: 'blur'}
        ]
      },
      captchaButtonTitle: this.$t('auth.captchaGot'),
      isCaptchaGetting: false,
      count: 0,
      intervalId: 0,
    };
  },
  watch:{
    isCaptchaGetting: function(val, oldval){
      if(val){
        this.count = 60;
        this.captchaButtonTitle = this.$t('reset.waiting')+this.count;

      }
      else{

      }
    }
  },
  methods:{
    resetPassword: function(){
      let param_r = {"resetPass_code": this.model.resetPass_code,
                     "resetPass_key": this.model.resetPass_key,
                     "password":this.model.password};

      axios.post('/reset/password', param_r).then(res=>{
        this.$router.push("/login");
      }).error(error=>{
        console.error(error);
      })
    },
    cancel: function(){
      this.$router.push("/");
    },
    getCaptcha: function(){
      let param_t = {phone: this.model.phone};
      let self = this;
      axios.post('/reset/code', param_t).then(res=>{
        console.log(res);
        self.model.resetPass_key = res.data.key;
        self.isCaptchaGetting = true;
        self.count = 60;
        self.intervalId = setInterval(() => {
            self.count=self.count-1;
            if(self.count<=0){
              self.isCaptchaGetting = false;
              self.captchaButtonTitle = self.$t('auth.captchaGot');
              clearInterval(self.intervalId);
            }
            else{
              self.captchaButtonTitle = self.$t('reset.waiting')+this.count;
            }
        }, 1000);
      }).catch(error=>{
        console.error(error);
      })
    },

  }

}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  @import '../../styles/variables';
  .reset {
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
      .el-input{
        width: 250px;
        vertical-align: top;
        margin-right: 10px;
      }
      .captcha{
        width:120px;
        vertical-align: top;
        margin-right: 10px;
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
