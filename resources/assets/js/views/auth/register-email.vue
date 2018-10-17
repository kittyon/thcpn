<template>
  <section class="register">
    <header class="register-header">
      <h1 class="brand"><router-link to="/" tabindex="-1">THC</router-link></h1>
      <el-alert v-if="error" :title="error.title" type="warning" :description="error.message" show-icon/>
    </header>

    <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="registers" label-position="left">
      <h2 class="heading">{{$t('auth.register')}}</h2>
      <el-form-item prop="type">
        <el-button-group>
          <el-button type="info" @click="toEmail">
            {{$t('auth.toEmail')}}
          </el-button>
          <el-button type="primary" @click="toPhone">
            {{$t('auth.toPhone')}}
          </el-button>
        </el-button-group>


      </el-form-item>
      <el-form-item prop="email">
        <el-input type="text" v-model="model.email" :placeholder="$t('auth.emailPlaceholder')"/>
      </el-form-item>
      <el-form-item prop="password">
        <el-input type="password" v-model="model.password" :placeholder="$t('auth.passwordPlaceholder')"/>
      </el-form-item>
      <el-form-item prop="confirmPassword">
        <el-input type="password" v-model="model.confirmPassword" :placeholder="$t('auth.passwordPlaceholder')"/>
      </el-form-item>
      <div class="captcha-container" v-if="captcha_image_content_ed">
        <el-form-item prop="captcha_code">
            <el-row>
              <el-col :span="10">
                <el-input v-model="model.captcha_code" :placeholder="$t('auth.captchaCode')">
                </el-input>
              </el-col>
              <el-col :span="8">
                <img  style="margin:2px" :title="$t('auth.captchaTitle')" v-bind:src="captcha_image_content_ed" @click="get_captcha()"/>
              </el-col>
            </el-row>
        </el-form-item>
      </div>
      <el-form-item>
        <el-button type="primary" :loading="loading" @click="submit">{{$t('auth.register')}}</el-button>
      </el-form-item>
    </el-form>
    <el-dialog :title="$t('auth.validateEmail')" :visible.sync="dialogRegVisible" :before-close="handleClose">
      <el-form :rules="rules" ref="dataForm" :model="model" label-position="left" label-width="70px" style='width: 50%, margin-left:50px;'>
        <el-form-item :label="$t('auth.key')" prop="key">
          <el-input v-model="model.verification_code">
            <el-button  slot="append" :disabled="!canSend" @click="send(()=>{})">{{regCodeInfo}}</el-button>
          </el-input>

        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogRegVisible = false">{{$t('config.cancel')}}</el-button>
        <el-button type="primary" @click="register">{{$t('config.confirm')}}</el-button>
      </div>
    </el-dialog>
    <footer class="login-footer clearfix">
      <router-link :to="{ name: 'login'}"  style="float: right;color: #3C8DBC;font-size: 14px; margin:2px">{{$t('auth.login')}}</router-link>
    </footer>
  </section>
</template>
<script>
export default {
  name: 'register',
  title: 'Register « THC | make IT better',
  data () {
    var validatePass = (rule, value, callback)=>{
      if (value === '') {
          callback(new Error(this.$t('error.password')));
        } else {
          if (this.model.confirmPassword !== '') {
            this.$refs.registers.validateField('confirmPassword');
          }
          callback();
        }
    };

    var validateConfirmPass = (rule, value, callback)=>{
      console.log(value);
      if (value === '') {
          callback(new Error(this.$t('error.confirmPassword')));
        } else if (value !== this.model.password) {
          callback(new Error(this.$t('error.diffrentPassword')));
        } else {
          callback();
        }
    };
    // form model
    // TODO: remove default values
    var model = {
      captcha_code:"",
      captcha_key:"",
      verification_key: "",
      verification_code : "",
      name : "",
      email : "",
      password : "",
      confirmPassword: ""
    }
    // form validate rules
    const rules = {
      name: [
        { required: true, message: '请输入用户名' },
        { min: 2, max: 16, message: '长度在 2 到 16 个字符' }
      ],
      password: [
        { required: true, message: '请输入密码' },
        { min: 6, max: 16, message: '长度在 6 到 16 个字符' },
        {validator: validatePass, trigger: 'change'}
      ],
      confirmPassword:[
        {validator: validateConfirmPass, trigger: 'change'}
      ],
      email:[
        {required: true, message: '请输入email'},
      ],
      captcha_code: [
        { required: true, message: '请输入验证码', trigger: 'blur' },

      ]

    }
    return { model: model, rules: rules, error: null, loading: false,
      canSend:false,regCodeInfo:this.$t('auth.send'),intervalId:0,count:60,
       dialogRegVisible: false, captcha_image_content:"" }
  },
  computed:{
    captcha_image_content_ed: function(){
      return this.captcha_image_content;
    },
    /*canSend: function(val, oldval){
      if(!val){
        this.count = 60;
        this.regCodeInfo = this.$t('reset.waiting')+this.count;

      }
    }*/
  },
  methods: {
    handleClose(done){
      this.$confirm('确认关闭？')
          .then(_ => {
            done();
          })
          .catch(_ => {});
    },
    toEmail(){
      this.$router.push({path:'/register/email'});
    },
    toPhone(){
      this.$router.push({path:'/register/phone'});
    },
    send(done){
      let params = {
        captcha_code:this.model.captcha_code,
        captcha_key:this.model.captcha_key
      };
      var self = this;
      axios.post('verificationCodes/email', params).then(function(res){
        self.model.verification_key = res.data.key || "";
        self.intervalId = setInterval(() => {
            self.count=self.count-1;
            if(self.count<=0){
              self.canSend = true;
              self.regCodeInfo = self.$t('auth.send');
              clearInterval(self.intervalId);
            }
            else{
              self.regCodeInfo = self.$t('reset.waiting')+self.count;
            }
        }, 1000);
        done();
      }).catch(err => {
        console.log(err);
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
        }
      });
    },
    submit(){
      this.$refs.registers.validate(valid => {
        if (!valid) return false
        // validated
        this.error = null
        //this.loading = true
        if(this.captcha_image_content){
          //get the code
          /*let params = {
            captcha_code:this.model.captcha_code,
            captcha_key:this.model.captcha_key
          };
          var self = this;
          axios.post('verificationCodes/email', params).then(function(res){
            self.model.verification_key = res.data.key || "";
            self.dialogRegVisible = true;
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
          */
          var self = this;
          this.send(()=>{self.dialogRegVisible = true;});
        }
        else{
          this.get_captcha();
        }
      });

    },
    get_captcha(){
      self = this;
      let params = {type:'email', email: this.model.email}
      axios.post("captchas",params).then(function(res){
        self.captcha_image_content = res.data.captcha_image_content;
        self.model.captcha_key = res.data.captcha_key;
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
      });
    },

    register(){
      let params = {
        verification_key: this.model.verification_key,
        verification_code : this.model.verification_code,
        name : this.model.email,
        email : this.model.email,
        password : this.model.password,
        type: 'email'
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
