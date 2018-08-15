<template>
  <div>
    <div class="sub-navbar draft">
        <span v-if="orgs.length > 1" class="sub-navbar-header" style="margin-right:15px; color: #fff;">{{$t('data.organization')}}</span>
        <el-select v-if="orgs.length > 1" class="sub-navbar-header" v-model="current_org">
          <el-option v-for="org in orgs" :value="org.id" :key="org.id" :label="org.name"></el-option>
        </el-select>
    </div>
    <el-form :model="options" :rules="rules" ref="options" label-width="80px" style="margin-top:20px">
      <el-form-item :label="$t('data.device')" prop="devices">
        <el-select
          v-model="options.device_index"
          :placeholder="$t('data.devicePlaceholder')">
          <el-option
            v-for="(item, index) in devices"
            :key="item.id"
            :label="item.name"
            :value="index">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('data.date')" prop="dates">
        <el-date-picker
          v-model="options.dates"
          type="datetimerange"
          :range-separator="$t('data.to')"
          :start-placeholder="$t('data.startDate')"
          :end-placeholder="$t('data.endDate')">
        </el-date-picker>
      </el-form-item>
      <el-form-item :label="$t('data.detailContent')" prop="contents">
        <el-select
         v-model="options.type"
         :placeholder="$t('data.contentPlaceholder')">
         <el-option
         v-for="item in types"
         :key="item.key"
         :label="item.name"
         :value="item.key">
         </el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button @click="handleGetData" type="success">{{$t('data.detailExecute')}}</el-button>
      </el-form-item>
    </el-form>
    <el-row style="background:#fff;padding:16px 16px 0;padding-bottom:32px;" v-for="(chart,key) in charts" :key="key">
      <el-card class="box-card" >
        <div slot="header" class="clearfix">
          <span>{{chart.name}}</span>
        </div>
        <highcharts :options="chart" ref="highcharts"></highcharts>
      </el-card>
    </el-row>
    <device-picture :images="images"/>
  </div>
</template>
<script>
import picture from "../device/picture.vue";
import types from "../../../config/types.js";
import api from '../../../api';
import {mapState} from "vuex";
export default{
  components:{
    "device-picture": picture
  },
  watch:{
    current_org: function(val, oldval){
      this.$cookie.set('current_org',val);
      this.getDevice(val);
    }
  },
  data(){
    var validateDevice = (rule, value, callback)=>{
      if(value.length == 0){
        callback(new Error($t('error.device')));
      }
      else{
        callback();
      }
    };
    var validateDate = (rule, value, callback)=>{
      if(value.length < 2){
        callback(new Error($t('error.date')));
      }
      else if(moment(value[1]).diff(moment(value[0]), "days")>30){
        callback(new Error($t('error.dateRange')));
      }
      else{
        callback();
      }
    };

    return {
      charts:[],
      images:{},
      devices:[],
      options: {
        device_index:0,
        dates:[],
        type:''

      },
      orgs:[],
      current_org:0,
      types: types,
      rules:{
        devices:[
          { required: true, message: $t('error.device'), trigger: 'blur' },
          {validator: validateDevice, trigger: 'blur'}
        ],
        dates:[
          { required: true, message: $t('error.date'), trigger: 'blur' },
          { validator: validateDate, trigger: 'blur'}
        ],
        contents:[
          { required: true, message: $t('error.content'), trigger: 'blur' },

        ],

      },
    };
  },
  computed:{
    ...mapState(
      {
        user: state=> state.user.Current
      })
  },
  created: function(){
    this.getOrg();
    this.getDevice(this.current_org);
  },
  methods:{
    getOrg: function(){
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
    getDevice(current_org){
      var self = this;
      var path = 'devices';
      var params_q = {
      }
      if(current_org>0){
        params_q['org_id'] = current_org;
      }

      axios.get(path,{params: params_q}).then(function (res) {
        self.devices = res.data.data;
        self.options.device_index = 0;
        }).catch(err=>{
        console.error(err)
        self.error = { title: $t('error.title'), message: $t('error.default') }
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = $t('error.auth')
            break
          case 500:
            self.error.message = $t('error.server')
            break
        }
      });
    },
    handleGetData(){
      this.getData(this.options);
    },
    getData(options){
      this.images = {};
      this.charts = [];
      var query = {
        start_at: options.dates[0],
        end_at: options.dates[1],
        limit: 10000,
      };
      var self = this;

      api.getDeviceData(self.devices[options.device_index] , query, function (err, data) {
        api.getDeviceConfig(self.devices[options.device_index],(err,cf)=>{
          if(cf.version == '2.0'){
            cf.data = api.parse_config_v2(cf);
          }
          var res = api.data2lists(self.devices[options.device_index], data, cf.data);
          if (options.type == 'image') {
            var images = _.filter(res, {type:options.type});

            self.images = _.reduce(images, function(res, v,k){
              res[k] = res[k]|| {data:[], name: v.name};
              res[k].data = _.reduce(v.data, function (memo, value) {
                var ts = moment(value.ts).format('YYYY-MM-DD');
                memo[ts] = memo[ts] || {date: ts, images:[]};
                memo[ts].images.push(value)
                return memo;
              },{});
              return res;
            },{});

          } else {
            self.charts = api.data2charts(self.devices[options.device_index], _.filter(res,{type: options.type}), cf.data, true);
          }

        });
      });
    },
  }

}
</script>
