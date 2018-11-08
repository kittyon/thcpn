<template>
  <div class="app-container">
    <el-row class="sub-navbar draft full-height">
      <div style="float: left;padding-left: 20px;">
        <span style="color: rgba(0, 0, 0, 0.45);font-size: 16px;">{{device.name}}</span>
      </div>
      <div class="right-group">
        <router-link :to="{name: 'main-devices-map'}">
          <el-button type="success">返回</el-button>
        </router-link>
      </div>
    </el-row>
    <el-row>
      <el-col :span="12">
        <el-tabs type="card" style="margin-top:20px">
          <el-tab-pane label="数据">
            <el-row>
              <el-col :span="12" v-for="(chart,key) in charts" :key="key">
                <el-card  class="box-card" style="margin: 5px">
                  <highcharts :options="chart" ref="highcharts" style="height: 140px;"></highcharts>
                </el-card>
              </el-col>
            </el-row>
          </el-tab-pane>
        </el-tabs>
      </el-col>
      <el-col :span="12">
        <device-picture :images="images"/>
      </el-col>
  </el-row>

  </div>
</template>

<script>
import api from '../../../api';
import picture from '../device/picture.vue';

export default {
  name: 'device-datas',
  components:{
    'device-picture': picture,
  },
  data(){
    return {
      charts:{},
      dataUrl: "",
      device:{},
      images:{},
      currentOrg:0,
    }
  },
  created: function(){
    var self = this;
    axios.get('taihe/device/'+this.$route.params.id).then(res=>{

      self.device = res.data;
      this.loadDeviceData(this.device);
    });


  },
  methods:{
    loadDeviceData (device) {

        var query = {
          start_at: moment().subtract(1,'days').format('YYYY-MM-DD hh:mm:ss'),
          end_at: moment().add({hours: 1}).format('YYYY-MM-DD hh:mm:ss'),
          limit: 10000,
        };
        var self = this;
        api.getDeviceData(device , query, function (err, data) {
          api.getDeviceConfig(device,(err,cf)=>{
            if(cf.version == '2.0'){
              cf.data = api.parse_config_v2(cf);
            }

            self.charts = api.data2charts(device,data,cf.data);
            var images = api.data2Images(device,data,cf.data);
            console.log(images);
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
          });
        });
      },
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
