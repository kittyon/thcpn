<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{$t('account.resetPassword')}}</span>
      </div>
      <el-form :model="reset" :rules="rules" ref="reset" label-width="80px">
        <el-form-item :label="$t('account.oldPassword')" prop="password">
          <el-input v-model="reset.password" type="password" style="max-width: 200px"></el-input>
        </el-form-item>
        <el-form-item :label="$t('account.newPassword')" prop="newPassword">
          <el-input v-model="reset.newPassword" type="password" style="max-width: 200px"></el-input>
        </el-form-item>
        <el-form-item :label="$t('account.confirmPassword')" prop="confirmPassword">
          <el-input v-model="reset.conformPassword" type="password" style="max-width: 200px"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button @click="resetPassword" type="success">{{$t('account.update')}}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>{{$t('account.changeInfo')}}</span>
      </div>
      <el-button @click="changeUserName">{{$t('account.changeName')}}</el-button>
    </el-card>


    <el-dialog :title="$t('account.changeName')" :visible.sync="dialogChangeNameVisible">
      <el-input v-model="userName"></el-input>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogChangeNameVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="updateName">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
export default{
  data(){
    var validatePass = (rule, value, callback)=>{
      if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (this.reset.checkPass !== '') {
            this.$refs.reset.validateField('confirmPassword');
          }
          callback();
        }
    };

    var validateConfirmPass = (rule, value, callback)=>{
      if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.reset.newPassword) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
    };
    return {
      reset:{
        password:"",
        newPassword:"",
        confirmPassword:""
      },
      dialogChangeNameVisible:false,
      rules:{
        password: [
            { required: true, message: '请输入旧密码', trigger: 'blur' },
          ],
          newPassword:[
            {validator: validatePass, trigger: 'blur'}
          ],
          confirmPassword:[
            {validator: validateConfirmPass, trigger: 'blur'}
          ]
      },
      userName:""
    }
  },
  methods:{
    resetPassword: function(){
      axios.post('resetpassword',this.reset).then(res=>{

      }).catch(error=>{

      });
    },
    changeUserName: function(){
      this.userName = this.$store.getters.user.name;
      this.dialogChangeNameVisible = true;
    },
    updateName: function(){
      let param_t = {"name": this.userName};
      let self = this;
      axios.put("user", param_t).then(res=>{
        self.userName = res.data.name;
      })
    }
  }
}
</script>
