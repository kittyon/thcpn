<template>
    <div>

      <baidu-map class="map" :zoom="zoom" :center="center" :scroll-wheel-zoom="true">
        <bm-scale anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-scale>
        <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" type="BMAP_HYBRID_MAP" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
        <bm-navigation anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-navigation>
        <bm-marker v-for="marker in markers" :key="marker.key"
          :icon="marker.icon" :shadow="{url: 'images/water-gray.png', size:{width:24, height:24},opts:{imageOffset:{ width:0, height: 12}}}"
         :position="marker.position" @click="marker.events.click" :title="marker.title" :dragging="marker.draggable">
          <!--<bm-label :content="marker.name" :labelStyle="{color: 'red', fontSize : '4px'}" :offset="{width: -15, height: 30}"/>-->
       </bm-marker>
      </baidu-map>
 </div>
</template>
<script>
import {Message} from 'element-ui'
import api from '../../../api';
import devices from './device-taihe-data.js'
export default {
  name: 'device-map',
  data() {
    return {
      zoom: 11,
      // center: [121.5273285, 31.21515044],
      center: {lng:114.9089,lat:26.79},
      markers: [
      ],
      error: null,
      current_org: 0
    };
  },
  created: function () {
    var self = this;
    this.load();
  },
  methods:{
    onOrgChanged: function(org){
      this.load();
    },
    load: function () {
  	  var self = this;
      self.markers = _.map(devices, function (v) {
        if(v.id){
          return {
            position: {lng:v.lon, lat:v.lat},
            events: {
              click: () => {
                self.$router.push('/device/'+v.id);
                // self.$router.push('/station');
              },
            },
            key: v.id,
            name: v.name,
            visible: true,
            icon:{url: 'images/'+v.icon, size: {width: 24, height: 24}},
            // content: 'xxxx',
            title: "名称："+v.name+"\n经度："+v.lon+"\n纬度："+v.lat,
          };
        }
        else{
          return {
            position: {lng:v.lon, lat:v.lat},
            events: {
              click: () => {
                //self.$router.push('/device/'+v.id);
                // self.$router.push('/station');
              },
            },
            key: v.name,
            name: v.name,
            visible: true,
            icon:{url: 'images/'+v.icon, size: {width: 24, height: 24}},
            // content: 'xxxx',
            title: "名称："+v.name+"\n经度："+v.lon+"\n纬度："+v.lat,
          };
        }

      });

    },
  }
}
</script>
<style>
/* 地图容器必须设置宽和高属性 */
.map {
  width: auto;
  height: 840px;
}
</style>
