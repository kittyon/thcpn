<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <div class="right-group">
        <el-button class="filter-item" v-if="!editing" @click="configEdit" type="success">{{$t('config.edit')}}</el-button>
        <el-button class="filter-item" v-if="editing" @click="confirmEdit" type="primary">{{$t('config.confirm')}}</el-button>
        <el-button class="filter-item" v-if="editing" @click="cancelEdit" type="info">{{$t('config.cancel')}}</el-button>
        <el-button class="filter-item" v-if="editing" type="primary" icon="el-icon-edit" @click="handleAdd">{{$t('config.add')}}</el-button>
      </div>
    </div>
    <el-table :key='tableKey' :data="tmpdata" v-loading="listLoading" element-loading-text="给我一点时间" border fit highlight-current-row
      style="width: 100%">
      <el-table-column align="center" :label="$t('config.key')" width="80">
        <template slot-scope="scope">
          <span>{{scope.row.key}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.name')" min-width="150px">
        <template slot-scope="scope">
          <el-input :disabled="!editing" v-model="scope.row.name"/>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.port')" min-width="80px">
        <template slot-scope="scope">
          <el-select v-model="scope.row.port" :disabled="!editing">
            <el-option v-for="item in ports"  :key="item" :value="item" :label="item"/>
          </el-select>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.sensorType')" min-width="150px">
        <template slot-scope="scope">
          <el-select v-model="scope.row.sensor_type" :disabled="!editing">
            <el-option v-for="item in sensors" :key="item.name" :value="item.name" :label="item.name+item.desc"/>
          </el-select>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.desc')" min-width="150px">
        <template slot-scope="scope">
          <el-input :disabled="!editing" v-model="scope.row.desc"/>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('config.actions')" width="65">
        <template slot-scope="scope">
          <el-button v-if="editing" type="text" @click="handleDel(scope.row)"><i class="fa fa-times-circle"></i></el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="$t('config.add')" :visible.sync="dialogAddVisible">
      <el-form :rules="rules" ref="dataForm" :model="tempRow" label-position="left" label-width="70px" style='width: 50%, margin-left:50px;'>
        <el-form-item :label="$t('config.key')" prop="key">
          <el-input v-model="tempRow.key"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddVisible = false">{{$t('config.cancel')}}</el-button>
        <el-button type="primary" @click="addData">{{$t('config.confirm')}}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import ports from "./config/v1/ports"
import sensors from "./config/v1/sensors"

export default{
  name:"device-config-data-v1",
  props:['cData'],
  data(){
    return {
      tableKey: 0,
      listLoading: false,
      tmpdata:{},
      dialogAddVisible: false,
      tempRow:{ key: '', name: '', sensor_type: '', port: ''},
      editing: false,
      sensors: sensors,
      ports: ports,
      rules:{key: [{ required: true, message: 'key is required', trigger: 'change' }]}
    };
  },
  created:function(){
    this.tmpdata = _.map(this.cData, function(v,k){
              return _.merge(v, {'key': k});
              });
  },
  methods:{
    handleDel(row){
      _.remove(this.tmpdata, function(d){
        return d.key == row.key;
      });
      this.tmpdata.sort();
    },
    handleAdd(){
      this.tempRow['key'] = "";
      this.dialogAddVisible = true;
    },
    configEdit(){
      this.editing = !this.editing;
    },
    confirmEdit(){
      var self = this;
      var vdata = _.reduce(this.tmpdata, function(res, v){
        res[v.key] = _.merge(_.omit(v, 'key'), _.find(sensors, {name: v.sensor_type}));
        return res;
      });
      this.$emit('configData', vdata);
      this.editing = !this.editing;
    },
    cancelEdit(){
      this.editing = !this.editing;
    },
    addData(){
      this.tmpdata.push(_.cloneDeep(this.tempRow));
      this.dialogAddVisible = false;
    }
  }
}
</script>
<style>
.right-group{
  float: right;
}
</style>
