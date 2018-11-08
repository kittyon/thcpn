import defaultOption from "./chartsOption"

export default{
  get_current_org(){
    var current_org = parseInt(Cookie.get('current_org'));
    if(isNaN(current_org)){
      current_org = 0;
    }
    return current_org;
  },


  parse_config_v2(config){
    let config_params = {}
    _.each(config.data, function(v){
      config_params = _.merge(config_params,
         _.reduce(v.params, function(result, item){
        result[item.key] = item;
        return result;
      },{}));
    });
    return config_params;
  },


  parse_data(data, config_params){
    return _.reduce(data, function(result, value, key){
      if(_.has(config_params, key)){
        (result|| (result = array())).push(_.merge(config_params[key],value));
      }
    })
  },

  data2lists(device,data,config){
    var res = {};
    _.forIn(data, function(item) {

        _.forIn(item.data, function(value, key) {
            item.data[key] = _.assign(item.data[key], {
                ts: item.ts
            });
            if(config[key]){

              if (!res[key]) {

                  res[key] = {
                    data:[],
                    name: config[key].desc+" "+config[key].unit,
                    unit: config[key].unit||"",
                    type: config[key].type||""};
                  }

              res[key].data.push(item.data[key]);
            }
        });
    });
    _.each(res, (v)=> {
        if (v.type == 'image') {
            v.data = _.orderBy(v.data, ['ts'], ['desc']);
        }
    });
    return res;
  },
  getDeviceData(device, query, callback) {
        //if (!_.isArray(devices)) devices = [devices];
        query = query || {}
        query.limit = 10000;
        var options = {
            params: query,
        }
        var uri = '/taihe/device/'+device.id+'/datas';
        var request = axios.get(uri, options).then(function (res) {
                callback(null, res.data.data||[]);
            });

  },
  getDeviceConfig(device, callback){
    var uri = '/taihe/device/'+device.id+'/config';
    let request = axios.get(uri).then(res=>{
      callback(null,res.data||{});
    });
  },

  data2Images(device, data, config, hasList = false){
    var self = this;
    var res = self.data2lists(device, data, config);
    console.log(res);
    return _.filter(res, {type: 'image'});
  },
  data2charts(device, data, config, hasList = false){
    console.log(config)

    var self = this;
    var res;
    if(hasList){
      res = data;
    }
    else{
      res = self.data2lists(device, data, config);
    }
    var charts = {};
    _.forIn(res, function(v,k){
      if(v.type == "image") return;
      if(v.type == "pressure") return;
      var type = v.type;
      charts[k] = charts[k] || _.cloneDeep(defaultOption);
      charts[k].name = charts[k].name|| v.name;
      charts[k] = _.merge({},charts[k],{
        yAxis: {
            title_v: {
                text: v.unit
            },

        }
      });
      var serieData =  _.map(v.data, function (dd) {
                        return [moment(dd.ts).toDate().getTime(), parseFloat(dd.value)];
                    });
      var serie = {
                    name: v.name,
                    data: serieData,
      }
      if (type == 'rainfall') {
          charts[k] = _.merge({}, charts[k], {
            chart: {
                type: 'column'
            },
          })
          serie.data = self.accumlateByTime(serie.data);
      }
      /*if (type == 'wind-direction'){
          serie.data = self.averageByTime(serie.data);
          serie.data = _.map(serie.data, function(v){
            return {
              x: v[0],
              y: v[1],
              marker: { symbol: self.getDirMarker(v[1])},
              width: 26,
              height: 26,
            }
          });
      }*/
      if (type == 'wind-velocity'){
          serie.data = self.averageByTime(serie.data);
      }
      charts[k].series.push(serie);
    });
    return JSON.parse(JSON.stringify(charts));
  },

  accumlateByTime (data) {
        var res = _.reduce(data, function (result, v, k) {
            var t = moment(v[0]).format("YYYY-MM-DD HH:00:00");
            result[t] = result[t] || 0;
            result[t] += v[1];
            return result;
        }, {});
        return _.map(res, function (v,k) {return [moment(k).toDate().getTime(),v]});
    },
    averageByTime(data){
      var res = _.reduce(data, function (result, v, k) {
          var t = moment(v[0]).format("YYYY-MM-DD HH:00:00");
          result[t] = result[t] || [0,0];
          result[t][0] += v[1];
          result[t][1] += 1;
          return result;
      }, {});
      return _.map(res, function(v,k){ return [new Date(k).getTime(), v[0]/v[1]]});
    },
    getDirMarker(v){
      var self = this;
      return self.getMarkers()[Math.floor(v/22.5)];
    },
    getMarkers(){
      return ['url(/images/north.png)',
      'url(/images/east_north.png)',
      'url(/images/east_north.png)',
      'url(/images/east.png)',
      'url(/images/east.png)',
      'url(/images/east_south.png)',
      'url(/images/east_south.png)',
      'url(/images/south.png)',
      'url(/images/south.png)',
      'url(/images/west_south.png)',
      'url(/images/west_south.png)',
      'url(/images/west.png)',
      'url(/images/west.png)',
      'url(/images/west_north.png)',
      'url(/images/west_north.png)',
      'url(/images/north.png)',
      ]
    },
}
