<template>
  <div>
    <el-row style="background:#fff;padding:16px 16px 0;padding-bottom:32px;" v-for="(chart,key) in charts" :key="key">
      <el-card class="box-card" >
        <div slot="header" class="clearfix">
          <span>{{chart.name}}</span>
          <router-link style="float: right; padding: 3px 0" type="text" :to="dataUrl + key"></router-link>
        </div>
        <highcharts :options="chart" ref="highcharts"></highcharts>
      </el-card>
    </el-row>
  </div>
</template>

<script>
import api from '../../../api';

export default {
  name: 'device-datas',
  data(){
    return {
      charts:{},
      dataUrl: "",
      device:{},
      currentOrg:0,
    }
  },
  created: function(){
    this.device = JSON.parse(localStorage.getItem('device')||"{}");
    this.currentOrg = parseInt(localStorage.getItem('org')||"0");
    this.loadDeviceData(this.device);
  },
  methods:{
    loadDeviceData (device) {
        var query = {
          start_at: moment().subtract(7,'day').format('YYYY-MM-DD'),
          end_at: moment().add(1,'day').format('YYYY-MM-DD'),
          limit: 10000,
        };
        var self = this;
        api.getDeviceData(device , query, function (err, data) {
          api.getDeviceConfig(device,(err,cf)=>{
            if(cf.version == '2.0'){
              cf.data = api.parse_config_v2(cf);
            }
            console.log(data);
            self.charts = api.data2charts(device,data,cf.data);
          });
        });
      },
  }

}
</script>
