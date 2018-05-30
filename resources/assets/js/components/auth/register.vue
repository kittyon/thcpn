<template>
  <section class="register">
    <header class="register-header">
      <h1 class="brand"><router-link to="/" tabindex="-1">THC</router-link></h1>
      <el-alert v-if="error" :title="error.title" type="warning" :description="error.message" show-icon/>
    </header>
    <el-steps :active="active" finish-status="success">
      <el-step title="步骤 1"></el-step>
      <el-step title="步骤 2"></el-step>
      <el-step title="步骤 3"></el-step>
      <el-step title="步骤 4"></el-step>
    </el-steps>
    <router-view v-on:actived="onActived"></router-view>
    <el-button type="primary" :loading="loading" @click="next">{{active == 4?'注册':'下一步'}}</el-button>
    <el-form class="register-form" auto-complete="off" :model="model" :rules="rules" ref="register-form" label-position="top">
      <h2 class="heading">注册</h2>
      <el-form-item prop="phone">
        <el-input type="text" v-model="model.phone" placeholder="请输入手机号"/>
      </el-form-item>
      <el-form-item prop="captcha">
          <el-input v-model="model.captcha" placeholder="请输入验证码">
            <el-button slot="append" @click='getCaptcha'>{{ captchaMsg }}</el-button>
          </el-input>
      </el-form-item>
      <el-form-item prop="verifyCode">
        <el-input v-model="model.verifyCode" placeholder="请输入验证码">
          <el-button slot="append" @click='getCode'>获取验证码</el-button>
        </el-input>
      </el-form-item>
      <el-button type="primary" :loading="loading" @click="submit('login-form')">{{ loading ? 'Loading...' : 'Register' }}</el-button>
    </el-form>
    <footer class="login-footer">
      ← Back to <a href="/">THCreate</a>
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
      captcha:'',
      verifyCode:''
    }
    // form validate rules
    const rules = {
      phone: [
        { required: true, message: '请输入用户名' },
        { min: 2, max: 16, message: '长度在 2 到 16 个字符' }
      ]
    }
    return { model: model, rules: rules, error: null, loading: false }
  },
  methods: {
    next(){

    },
    onActived(active){

    },
    submit (ref) {
      // form validate
      this.$refs[ref].validate(valid => {
        if (!valid) return false
        // validated
        this.error = null
        this.loading = true
        // create token from remote
        this.$store.dispatch('createToken', this.model)
          .then(token => {
            //get user_info
            axios.get('user').then(res=>{
              this.$store.commit('user', res.data);
            });
            this.$router.replace({ path: this.$route.query.redirect || '/' });
            this.loading = false
          })
          .catch(err => {
            console.error(err)
            this.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
            switch (err.response && err.response.status) {
              case 401:
                this.error.message = '用户名或密码错误！'
                break
              case 500:
                this.error.message = '服务器内部异常，请稍后再试！'
                break
            }
            this.loading = false
          })
      })
    }
  }
}
</script>

<style lang="scss">
  @import '../../styles/base/variables';
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
        width: 100%;
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
