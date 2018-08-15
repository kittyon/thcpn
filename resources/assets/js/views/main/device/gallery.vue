<template>
  <div>
    <device-picture :images="images"/>
  </div>
</template>

<script>
import api from '../../../api';
import picture from './picture.vue';
export default {
  name: 'device-gallery',
  components:{"device-picture":picture},
  data(){
    return {
      images:{},
      device:{},
      currentOrg:0
    }
  },
  created: function(){
    this.device = JSON.parse(localStorage.getItem('device')||"{}");
    this.currentOrg = parseInt(localStorage.getItem('org')||"0");
    this.loadDeviceData(this.device);
    this.activeName = _.keys(this.images)[0];
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
            }
            images = api.data2Images(device,data,cf.data);
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
