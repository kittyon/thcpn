<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <div class="right-group">
        <el-button class="filter-item" v-if="!editing" @click="configEdit">{{$t('config.edit')}}</el-button>
        <el-button class="filter-item" v-if="editing" @click="confirmEdit">{{$t('config.confirm')}}</el-button>
        <el-button class="filter-item" v-if="editing" @click="cancelEdit">{{$t('config.cancel')}}</el-button>
        <el-button class="filter-item" v-if="editing" type="primary" @click="handleAdd">{{$t('config.add')}}</el-button>
      </div>
    </div>
    <el-table :key='tableKey' :data="cData" v-loading="listLoading" element-loading-text="给我一点时间" border fit highlight-current-row
      style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="props">
          <el-table :data="props.row.params" border fit highlight-current-row :key="props.row.sensor_type">
            <el-table-column align="center" :label="$t('config.dataType')" width="150">
              <template slot-scope="scope">
                <span>{{sensors[props.row.sensor_type].data_nums[scope.$index].desc}}</span>
              </template>
            </el-table-column>
            <el-table-column align="center" :label="$t('config.key')" width="150">
              <template slot-scope="scope">
                <el-input v-model="scope.row.key" :disabled="!editing"/>
              </template>
            </el-table-column>
            <el-table-column align="center" :label="$t('config.name')" width="150">
              <template slot-scope="scope">
                <el-input v-model="scope.row.name" :disabled="!editing"/>
              </template>
            </el-table-column>
            <el-table-column align="center" :label="$t('config.desc')" width="150">
              <template slot-scope="scope">
                <el-input v-model="scope.row.desc" :disabled="!editing"/>
              </template>
            </el-table-column>
          </el-table>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.sensorType')" width="300">
        <template slot-scope="scope">
          <el-select v-model="scope.row.sensor_type" @change="handleSensorChanged(scope.row)" :disabled="!editing">
            <el-option v-for="(v,k) in sensors" :key="k" :value="k" :label="v.desc+scope.row.sensor_type"/>
          </el-select>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.port')" width="300">
        <template slot-scope="scope">
          <el-select v-model="scope.row.port_num" :disabled="!editing">
            <el-option v-for="item in sensors[scope.row.sensor_type].port_nums"  :key="item" :value="item" :label="item"/>
          </el-select>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('config.actions')" width="65">
        <template slot-scope="scope">
          <el-button v-if="editing" type="text" @click="handleDel(scope.$index)"><i class="fa fa-times-circle"></i></el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
import sensors from "./config/v2/sensors"

const defaultOption = {"port": "Img", "params": [{"key": "img_env", "desc": "", "name": "图片1", "type": "image", "unit": "", "data_num": 0}], "port_num": 0, "sensor_type": "hisi"};
export default{
  name:"device-config-data-v2",
  props:['cData'],
  data(){
    return {
      tableKey: 0,
      listLoading: false,
      sensors: sensors,
      tempRow:{ key: '', name: '', sensor_type: '', port: ''},
      editing: false
    };
  },
  methods:{
    handleDel(index){
      _.remove(this.cData, function(d,i){
        return i == index;
      });
      this.cData.sort();
    },
    handleSensorChanged(row){
      row['port'] = this.sensors[row.sensor_type].port;
      row['port_num'] = this.sensors[row.sensor_type].port_nums[0];
      row['params'] = _.assign(_.cloneDeep(this.sensors[row.sensor_type].data_nums),{name:''});
    },
    handleAdd(){
      this.cData.push(_.cloneDeep(defaultOption));
    },
    configEdit(){
      this.editing = !this.editing;
    },
    confirmEdit(){
      var self = this;
      var vdata = _.map(this.cData, function(v){
        v.port= self.sensors[v.sensor_type].port;
        return v;
      });
      this.$emit('configData', vdata);
      this.editing = !this.editing;
    },
    cancelEdit(){
      this.editing = !this.editing;
    }
  }
}
</script>
<style>
.right-group{
  float: right;
}
</style>
