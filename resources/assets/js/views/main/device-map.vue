<template>
    <div>
      <th-header v-on:orgChanged="onOrgChanged" ></th-header>
      <baidu-map class="map" :zoom="zoom" :center="center" :scroll-wheel-zoom="true">
        <bm-scale anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-scale>
        <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
        <bm-navigation anchor="BMAP_ANCHOR_BOTTOM_LEFT"></bm-navigation>
        <bm-marker v-for="marker in markers" :key="marker.key" :position="marker.position" @click="marker.events.click" :title="marker.title" :dragging="marker.draggable"></bm-marker>
      </baidu-map>
 </div>
</template>
<script>
import THeader from './th-header.vue'
import {Message} from 'element-ui'
import api from '../../api';
export default {
  name: 'device-map',
  components:{
    "th-header":THeader,
  },
  data() {
    return {
      zoom: 5,
      // center: [121.5273285, 31.21515044],
      center: {lng:116.405285,lat:39.904989},
      markers: [
      ],
      error: null,
      current_org: 0
    };
  },
  created: function () {
    var self = this;
    this.current_org = api.get_current_org();
    this.load(this.current_org);
  },
  methods:{
    onOrgChanged: function(org){
      this.current_org = org;
      this.load(this.current_org);
    },
    load: function (current_org) {
  	  var self = this;
      var path = 'devices';
      if(current_org>0){
        path = path+'?org_id='+current_org;
      }
      //console.log(path)
      axios.get(path).then(function (res) {
        var devices = res.data.data;
        self.markers = _.map(devices, function (v) {
          return {
            position: {lng:v.lon, lat:v.lat},
            events: {
              click: () => {
                self.$router.push('/device/'+v.id+'/dashboard');
                // self.$router.push('/station');
              },
            },
            key: v.id,
            visible: true,
            // content: 'xxxx',
            title: "名称："+v.name+"\n经度："+v.lon+"\n纬度："+v.lat,
          };
        });
      }).catch(err=>{
        console.error(err)
        this.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
        switch (err.response && err.response.status) {
          case 401:
            this.error.message = '用户名或密码错误！'
            break
          case 500:
            this.error.message = '服务器内部异常，请稍后再试！'
            break
        }
        Message.error(this.error);
      });
    },
  }
}
</script>
<style>
/* 地图容器必须设置宽和高属性 */
.map {
  width: auto;
  height: 540px;
}
</style>
