<template>
  <div class="app-container">
    <el-row class="sub-navbar draft full-height">
      <el-button :disabled="!isChanged" type="success" @click="uploadConfig">{{$t('config.save')}}</el-button>
      <el-button :disabled="!isChanged" type="button" @click="cancelUpload">{{$t('config.cancel')}}</el-button>
    </el-row>
    <el-row class="sub-navbar deleted full-height">
      <div class="left-group filter-container">
        <span class="config-title">{{$t('config.version')}}</span>
        <el-select v-model="tmp_version" :disabled="!v_editing" class="filter-item">
          <el-option v-for="item in configVersions" :key="item" :label="item" :value="item"/>
        </el-select>
        <el-button v-if="!v_editing" class="filter-item" @click="configVerEdit">{{$t('config.edit')}}</el-button>
        <el-button v-if="v_editing" class="filter-item" @click="confirmVerEdit">{{$t('config.confirm')}}</el-button>
        <el-button v-if="v_editing" class="filter-item" @click="cancelVerEdit">{{$t('config.cancel')}}</el-button>
      </div>
    </el-row>
    <el-row  type="flex" class="sub-navbar deleted full-height" justify="end">
      <el-col :span="12" v-for="(v,k) in control" :key="k">
        <div>
          <span class="config-title">{{cronOption[k]}}</span>
          <span class="config-title">{{v}}</span>
          <el-button @click="handleCronEdit(v, k)" type="button">{{$t('config.edit')}}</el-button>
        </div>
      </el-col>
    </el-row>
    <el-row>
      <div>
        <config-data-v1 v-if="isVersion1" :cData="config.data" @configData="handleDataChanged"/>
        <config-data-v2 v-else :cData="config.data" @configData="handleDataChanged"/>
      </div>
    </el-row>
    <!--<el-row>
      <div v-for="(v,k) in control">
        <span class="config-title">{{cronOption[k]}}</span>
        <span class="config-title">{{v}}</span>
        <el-button @click="handleCronEdit(v, k)" type="button"><i class="fa fa-edit"></i>{{$t('config.edit')}}</el-button>
      </div>
    </el-row>-->

    <el-dialog :title="cronOption[control_key]" :visible.sync="dialogCronVisible">
      <el-input slot="reference" v-model="cron" :placeholder="cronOption[control_key]"></el-input>
      <cron @change="changeCron" @close="dialogCronVisible == false" i18n="cn"></cron>
    </el-dialog>
  </div>
</template>

<script>
import config from "../../../config/index"
import configDataV1 from "./configDataV1.vue"
import configDataV2 from "./configDataV2.vue"
import {cron} from 'vue-cron'
import api from '../../../api';


export default {
  name: 'device-config',
  components:{
    "config-data-v1": configDataV1,
    "config-data-v2": configDataV2,
    "cron" :cron
  },
  data(){
    return {
      configVersions: config.api.configVersions,
      config:{},
      device:{},
      currentOrg:0,
      v_editing: false,
      editing: false,
      isVersion1:false,
      tmp_version: "",
      control: {},
      cron: "",
      cronOption: {
        img_collector_invl: "图片上传",
        data_capture_invl: "数据上传"
      },
      cronPopover: {},
      dialogCronVisible:false,
      isChanged: false,
      control_key:""
    };
  },
  created: function(){
    var self = this;
    this.device = JSON.parse(localStorage.getItem('device')||"{}");
    this.currentOrg = parseInt(localStorage.getItem('org')||"0");
    api.getDeviceConfig(this.device, function(err, data){
      self.config = data;
      self.isVersion1 = (self.config.version == '1.0');
      self.tmp_version = self.config.version;
      self.control = _.assign({img_collector_invl:"",data_capture_invl:""},self.config.control);
    });
  },
  methods:{
    configVerEdit(){
      this.v_editing = !this.v_editing;
    },
    confirmVerEdit(){
      this.v_editing = !this.v_editing;
      this.config.version = this.tmp_version;
      this.isChanged = true;
      if(this.config.version == '1.0'){
        this.isVersion1 = true;
        this.config.data = {};
      }
      else{
        this.isVersion1 = false;
        this.config.data = [];
      }

    },
    cancelVerEdit(){
      this.v_editing = !this.v_editing;
    },
    changeCron(val){
      this.cron = val;
      this.control[this.control_key] = val;
      this.dialogCronVisible = false;
    },
    handleCronEdit(v,k){
      this.control_key = k;
      this.cron = v;
      this.dialogCronVisible = true;
    },
    handleDataChanged(val){
      this.config.data = val;
      this.isChanged = true;
    },
    uploadConfig(){
      //create new config
      this.config.version = this.tmp_version;
      this.config.control = this.control;
      var self = this;
      var tmpConfig = {data: JSON.stringify(this.config.data), control: JSON.stringify(this.config.control), version: this.config.version};
      axios.post(this.$route.path,tmpConfig).then(res=>{
        this.isChanged = false;
        this.$notify({
          title: '成功',
          message: '设备配置成功',
          type: 'success',
          duration: 2000
        })
      }).catch(err=>{
        console.error(err);
      })
    },
    cancelUpload(){
      this.router.go(-1);
    }
  }
}
</script>
<style>
.full-height{
  height: 100%;
}
.sub-navbar {
  position: relative;
  width: 100%;
  padding-right: 20px;
  transition: 600ms ease position;
  background: linear-gradient(90deg, rgba(32, 182, 249, 1) 0%, rgba(32, 182, 249, 1) 0%, rgba(33, 120, 241, 1) 100%, rgba(33, 120, 241, 1) 100%)

  .left-group{
    float: left;
    padding-left: 20px;
    text-align: left;
    .device-name{
      color: rgba(0, 0, 0, 0.45);
      font-size: 16px;
    }
  }
  .right-group{
    float: right;
  }
  &.draft {
    background: #d0d0d0;
  }
  &.deleted {
    background: #d0d0d0;
  }
}

.config-title {
  color: rgba(0, 0, 0, 0.45);
  font-size: 16px;
  margin-right:20px;
}

</style>
