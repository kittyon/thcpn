<template>
  <div>
    <div class="sub-navbar">
        <span v-if="orgs.length > 1" class="sub-navbar-header" style="margin-right:15px; color: #fff;">用户或组织</span>
        <el-select v-if="orgs.length > 1" class="sub-navbar-header" v-model="current_org">
          <el-option v-for="org in orgs" :value="org.id" :key="org.id" :label="org.name"></el-option>
        </el-select>

        <!--<button class="btn btn-primary btn-xs" @click="back()">
          <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> 返回</button>-->
        <el-button @click="handleAttach">{{$t('device.attach')}}</el-button>
    </div>
    <list :org="current_org"/>

    <el-dialog :title="$t('device.attach')" :visible.sync="dialogAttachVisible">
      <el-input v-model="deviceIccid" :tooltip="$t('device.attachDescription')">
        <template slot="prepend">iccid</template>
      </el-input>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAttachVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="attachDevice">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import List from './device/list.vue'
import api from '../../api';
import {Message} from 'element-ui';
import {mapGetters} from "vuex";
export default {
  name: 'device-list',
  components:{
    "list":List
  },
  data(){
    return {
      error: null,
      current_org : 0,
      orgs:[],
      dialogAttachVisible: false,
      deviceIccid: ""

    }
  },
  computed:{
    ...mapGetters(
      {
        user:'user'
      })
  },
  created: function () {
    var self = this;
    this.current_org = api.get_current_org();

    var self = this;
    self.orgs.push({id:0, name: self.user.name});
    axios.get('organizations').then(res=>{
      self.orgs = _.concat(self.orgs, res.data.data);
    });
    if (isNaN(parseInt(this.$cookie.get('current_org')))) {
      this.$cookie.set('current_org',0);
      this.current_org = 0;
    }
    else{
      this.current_org = parseInt(this.$cookie.get('current_org'));
    }

  },
  methods:{
    handleAttach: function(){
      dialogAttachVisible = true;
    },
    attachDevice: function(){
      var param_t = {'iccid': this.deviceIccid};
      var self = this;
      if(current_org == 0){
        param_t['user_id'] = this.$store.getter.user.id;
      }
      else{
        param_t['org_id'] = this.current_org;
      }

      axios.post("/device/attach", param_t).then(res=>{
        self.dialogAttachVisible = false;
        self.$notify({
          title: '成功',
          message: '退出组织成功',
          type: 'success',
          duration: 2000
        })
      }).catch(error=>{
        console.error(error);
      })
    }
  }
}
</script>
<style>
.sub-navbar {
  position: relative;
  width: 100%;
  padding-right: 20px;
  transition: 600ms ease position;
  background: linear-gradient(90deg, rgba(32, 182, 249, 1) 0%, rgba(32, 182, 249, 1) 0%, rgba(33, 120, 241, 1) 100%, rgba(33, 120, 241, 1) 100%);
  .subtitle {
    font-size: 20px;
    color: #fff;
  }
  .sub-navbar-header{
    .el-badge__content {
      top: 14px;
      right: 7px;
      text-align: center;
      font-size: 9px;
      padding: 0 3px;
      background-color: #00a65a;
      color: #fff;
      border: none;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: .25em;
    }
    overflow: hidden;
    height: 50px;
    display: inline-block;
    text-align: center;
    line-height: 50px;
    cursor: pointer;
    padding: 0 14px;
    color: #fff;
    &:hover {
      background-color: #367fa9
    }
  }
}
</style>
