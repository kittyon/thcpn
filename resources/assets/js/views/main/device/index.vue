<template>
  <div>
    <el-row class="panel-group" :gutter="40">

      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <el-card class='box-card' shadow="hover">
          <div slot="header" class="clearfix">
            <span>{{$t('device.datas')}}</span>
            <el-button style="float: right; padding: 3px 0" type="text">more...</el-button>
          </div>
          <template>
            <div v-for="(d,key) in datas" :key="key">
              <span style="color: rgba(0, 0, 0, 0.45);font-size: 16px;">{{d.name}}</span>
              <count-to class="card-panel-num" :startVal="0" :endVal="d.data[0].value" :duration="1000"></count-to>
              <span style="color: rgba(0, 0, 0, 0.45);font-size: 16px;">{{d.unit}}</span>
            </div>
          </template>
        </el-card>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">

        <el-card class='box-card' shadow="hover">
          <div slot="header" class="clearfix">
            <span>{{$t('device.gallery')}}</span>
            <el-button style="float: right; padding: 3px 0" type="text">more...</el-button>
          </div>
          <template>
            <el-card :body-style="{ padding: '0px' }" style="margin-top: 5px">
              <img class="image" v-img:group :src="'http://thc-platfrom-storage.b0.upaiyun.com'+image.value" alt="alt"/>
              <div style="padding: 8px;">
                <div class="bottom clearfix">
                  <time class="time">{{ image.ts }}</time>
                </div>
              </div>
            </el-card>
          </template>
        </el-card>
      </el-col>
    </el-row>
    <!--pic part
    <el-row class="panel-group" :gutter="40">
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <el-card class='box-card' shadow="hover">
          <div slot="header" class="clearfix">
            <span>{{$t('device.weather')}}</span>
            <el-button style="float: right; padding: 3px 0" type="text">more...</el-button>
          </div>
        </el-card>
      </el-col>
    </el-row>-->
    <!--todo list-->
  </div>
</template>
<script>
import CountTo from 'vue-count-to'
import picture from './picture.vue';
import api from '../../../api';
export default {
  name: 'device-dashboard',
  components:{
    "count-to": CountTo,
    "device-picture":picture
  },
  data(){
    return {
      device:{},
      datas:{},
      image:{},
      currentOrg:0,
    }
  },
  created: function(){
    this.device = JSON.parse(localStorage.getItem('device')||"{}");
    this.currentOrg = parseInt(localStorage.getItem('org')||"0");
    this.getNewestData();
  },
  methods:{
    getNewestData(){
      var query = {
        params:{}
      }
      var self = this;
      if(this.currentOrg>0){
        query.params['org_id'] = this.currentOrg;
      }
      api.getDeviceConfig(self.device,(err,cf)=>{
        if(cf.version == '2.0'){
          cf.data = api.parse_config_v2(cf);
        }
        var uri = '/device/'+this.device.id+'/datas/new/data';
        axios.get(uri, query).then(function (res) {
            var data = [res.data];
            self.datas = api.data2lists(self.device, data, cf.data);
          });

        uri = '/device/'+this.device.id+'/datas/new/image';
        axios.get(uri, query).then(function (res) {
            self.image = {'ts':res.data.ts,'value': _.values(res.data.data)[0].value};
          });
        });
    }
  }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
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
.panel-group {
  margin-top: 18px;
  .card-panel-col{
    margin-bottom: 32px;
  }
  .card-panel {
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border-color: rgba(0, 0, 0, .05);
    &:hover {
      .card-panel-icon-wrapper {
        color: #fff;
      }
      .icon-people {
         background: #40c9c6;
      }
      .icon-message {
        background: #36a3f7;
      }
      .icon-money {
        background: #f4516c;
      }
      .icon-shoppingCard {
        background: #34bfa3
      }
    }
    .icon-people {
      color: #40c9c6;
    }
    .icon-message {
      color: #36a3f7;
    }
    .icon-money {
      color: #f4516c;
    }
    .icon-shoppingCard {
      color: #34bfa3
    }
    .card-panel-icon-wrapper {
      float: left;
      margin: 14px 0 0 14px;
      padding: 16px;
      transition: all 0.38s ease-out;
      border-radius: 6px;
    }
    .card-panel-icon {
      float: left;
      font-size: 48px;
    }
    .card-panel-description {
      float: right;
      font-weight: bold;
      margin: 26px;
      margin-left: 0px;
      .card-panel-text {
        line-height: 18px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 16px;
        margin-bottom: 12px;
      }
      .card-panel-num {
        font-size: 20px;
      }
    }
  }
}
</style>
