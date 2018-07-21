<template>
  <div>
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane v-for="(image,key) in images" :label="image.name" :name="key" :key="key">
        <el-card v-for="(iis, ts) in image.data" class="box-card" :key="ts">
          <div slot="header" class="clearfix">
            <span>{{ts}}</span>
          </div>
          <el-card v-for="i in iis" :key="i.ts">
            <img class="image" v-img:group :src="'http://thc-platfrom-storage.b0.upaiyun.com'+i.value" alt="alt"/>
            <div class="bottom clearfix">
              <span class="time">{{i.ts}}</span>
              <el-button style="float: right" type="text"><i class="fa fa-download"></i></el-button>
            </div>
          </el-card>
        </el-card>
      </el-tab-pane>
    </el-tabs>

  </div>
</template>

<script>
import api from '../../../api';

export default {
  name: 'device-gallery',
  data(){
    return {
      images:{},
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
        var images = {};
        api.getDeviceData(device , query, function (err, data) {
          api.getDeviceConfig(device,(err,cf)=>{
            if(cf.version == '2.0'){
              cf.data = api.parse_config_v2(cf);
              images = api.data2Images(device,data,cf.data);
            }
          });
        });
        this.images = _.reduce(images, function(res, v,k){
          res[k] = res[k]|| {data:[], name: v.name};
          res[k].data = _.reduce(v.data, function (memo, value) {
            var ts = moment(value.ts).format('YYYY-MM-DD');
            memo[ts] = memo[ts] || {date: ts, images:[]};
            memo[ts].images.push(value)
            return memo;
          },{});
          return res;
        },{});

      },
  }
}
</script>
<style>
.time {
    font-size: 13px;
    color: #999;
  }

  .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

  .button {
    padding: 0;
    float: right;
  }

  .image {
    width: 100%;
    display: block;
  }

  .clearfix:before,
  .clearfix:after {
      display: table;
      content: "";
  }

  .clearfix:after {
      clear: both
  }
</style>
