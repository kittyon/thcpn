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
            <span style="color: rgba(0, 0, 0, 0.45);font-size: 16px;">温度</span>
            <count-to class="card-panel-num" :startVal="0" :endVal="102400" :duration="2600"></count-to>
          </template>
        </el-card>
      </el-col>
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <el-card class='box-card' shadow="hover">
          <div slot="header" class="clearfix">
            <span>{{$t('device.gallery')}}</span>
            <el-button style="float: right; padding: 3px 0" type="text">more...</el-button>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <!--pic part-->
    <el-row class="panel-group" :gutter="40">
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <el-card class='box-card' shadow="hover">
          <div slot="header" class="clearfix">
            <span>{{$t('device.weather')}}</span>
            <el-button style="float: right; padding: 3px 0" type="text">more...</el-button>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <!--todo list-->
    <el-row class="panel-group" :gutter="40">
      <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
        <el-card class='box-card' shadow="hover">
          <div slot="header" class="clearfix">
            <span>{{$t('device.weather')}}</span>
            <el-button style="float: right; padding: 3px 0" type="text">more...</el-button>
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import CountTo from 'vue-count-to'
export default {
  name: 'device-dashboard',
  components:{
    "count-to": CountTo
  },
  data(){
    return {
      device:{},
      currentOrg:0,
    }
  },
  created: function(){
    this.device = JSON.parse(localStorage.getItem('device')||"{}");
    this.currentOrg = parseInt(localStorage.getItem('org')||"0");
  }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
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
