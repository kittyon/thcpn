<template>
  <el-row class="sub-navbar draft full-height">
    <div style="float: left;padding-left: 20px;">
      <span style="color: rgba(0, 0, 0, 0.45);font-size: 16px;">{{device.name}}</span>
    </div>
    <div class="right-group">
      <router-link :to="{name: 'device-dashboard', params:{id: device.id}}">
        <el-button type="success">{{$t('device.index')}}</el-button>
      </router-link>
      <router-link :to="{name: 'device-config', params:{id: device.id}}">
        <el-button type="primary" v-if="canEdit(device)">{{$t('device.config')}}</el-button>
      </router-link>
      <router-link :to="{name: 'device-datas', params:{id: device.id}}">
        <el-button v-if="canBrower(device)">{{$t('device.datas')}}</el-button>
      </router-link>
      <router-link :to="{name: 'device-gallery', params:{id: device.id}}">
        <el-button v-if="canBrower(device)">{{$t('device.gallery')}}</el-button>
      </router-link>
    </div>
  </el-row>
</template>
<script>
  export default {
    data: function () {
      return {
        device: {},
        currentOrg:0,
      }
    },
    created:function(){
      console.log("init for dev header")
      this.device = JSON.parse(localStorage.getItem('device')||"{}");
      this.currentOrg = parseInt(localStorage.getItem('org')||"0");
    },
    methods:{
      canEdit: function(d){
        if(this.currentOrg > 0){
          return this.$_has(d.perms,'org_w');
        }
        else{
          return this.$_has(d.perms,'dev_w');
        }
      },
      canBrower: function(d){
        if(this.currentOrg > 0){
          return this.$_has(d.perms,'org_r');
        }
        else{
          return this.$_has(d.perms,'dev_r');
        }
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
  background: linear-gradient(90deg, rgba(32, 182, 249, 1) 0%, rgba(32, 182, 249, 1) 0%, rgba(33, 120, 241, 1) 100%, rgba(33, 120, 241, 1) 100%);
  .subtitle {
    font-size: 20px;
    color: #fff;
  }
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

</style>
