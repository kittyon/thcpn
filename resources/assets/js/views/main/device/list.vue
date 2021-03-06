<template>
  <div>
    <!--<th-header v-on:orgChanged="onOrgChanged" ></th-header>-->
    <el-table :key='tableKey' :data="devices" v-loading="listLoading" :element-loading-text="$t('table.loading')" border fit highlight-current-row
      style="width: 100%">
      <el-table-column align="center" :label="$t('table.id')" width="65">
        <template slot-scope="scope">
          <span>{{scope.row.id}}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="150px" :label="$t('table.name')">
        <template slot-scope="scope">
          <span class="link-type" @click="handleUpdate(scope.row)">{{scope.row.name}}</span>
        </template>
      </el-table-column>
      <el-table-column width="110px" align="center" :label="$t('table.iccid')">
        <template slot-scope="scope">
          <span>{{scope.row.iccid}}</span>
        </template>
      </el-table-column>
      <el-table-column class-name="status-col" :label="$t('table.status')" width="100">
        <template slot-scope="scope">
          <el-tag :type="scope.row.status | statusFilter">{{scope.row.status}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('table.actions')" width="230" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button v-if="canEdit(scope.row)" type="primary" size="mini" @click="handleUpdate(scope.row)">{{$t('table.edit')}}</el-button>
          <el-button v-if="$_has(scope.row.perms,['dev_r','org_r'])" type="success" size="mini" @click="handleGoDevice(scope.row)">{{$t('table.detail')}}
          </el-button>
          <el-button v-if="canEdit(scope.row)" size="mini" type="danger" @click="handleDetach(scope.row)">{{$t('table.detach')}}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[2,20,30,50]" :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form :rules="rules" ref="dataForm" :model="temp" label-position="left" label-width="70px" style='width: 50%, margin-left:50px;'>
        <el-form-item :label="$t('table.version')" prop="version">
          <el-select class="filter-item" v-model="temp.version" placeholder="Please select">
            <el-option v-for="item in versionOptions" :key="item" :label="item" :value="item">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('table.name')" prop="name">
          <el-input v-model="temp.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('table.iccid')" prop="iccid">
          <el-input v-model="temp.iccid"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="createData">{{$t('table.confirm')}}</el-button>
        <el-button v-else type="primary" @click="updateData">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('table.detach')" :visible.sync="dialogDetachVisible">
      <span>{{$t('device.detachTitle')}}</span>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogDetachVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="detachData">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>


  </div>
</template>

<script>
import api from '../../../api';
import {Message} from 'element-ui'
export default {
  name: 'devList',
  props:['org'],
  data(){
    return {
      error: null,
      current_org : 0,
      orgs:[],
      tableKey: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 2
      },
      total: 0,
      textMap: {
        update: 'Edit',
        create: 'Create'
      },
      versionOptions:[
        "1.0", "2.0"
      ],
      rules: {
        name: [{ required: true, message: 'name is required', trigger: 'change' }],
        iccid: [{ required: true, message: 'iccid is required', trigger: 'change' }],
        version: [{ required: true, message: 'version is required', trigger: 'blur' }]
      },
      temp: {
        id: undefined,
        name: '',
        iccid: '',
        version: '1.0',
      },
      dialogStatus: '',
      dialogFormVisible: false,
      dialogDetachVisible: false,
      devices:[]
    }
  },
  watch:{
    org: function(val, oldval){
      this.current_org = val;
      this.load(this.current_org, this.listQuery);
    }
  },
  created: function () {
    var self = this;
    if(this.org){
      this.current_org = this.org.id;
    }
    else{
      this.current_org = api.get_current_org();
    }

    this.load(this.current_org, this.listQuery);
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        normal: 'normal',
        abnormal: 'abnormal',
      }
      return statusMap[status]
    }
  },
  methods:{
    canEdit: function(row){
      if(this.current_org > 0){
        return this.$_has(row.perms,'org_w');
      }
      else{
        return this.$_has(row.perms,'dev_w');
      }
    },
    handleGoDevice(row){
      this.$store.commit("device", row);
      this.$store.commit("org", this.current_org);

      this.$router.push({path:"/device/"+row.id});
    },
    handleSizeChange: function(val){
      this.listQuery.limit = val;
      this.load(this.current_org, this.listQuery);
    },
    handleCurrentChange: function(val){
      this.listQuery.page = val;
      this.load(this.current_org, this.listQuery);
    },
    handleUpdateConfig(row){
      this.$router.push('device/'+row.id);
    },
    handleUpdate(row) {
      this.temp = _.assign({}, row) // copy obj
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    handleDetach(row){
      this.temp = _.assign({}, row) // copy obj
      this.dialogDetachVisible = true
    },
    detachData(){
      const tempData = _.assign({}, this.temp);
      let params = _.assign({});
      if(this.current_org > 0)
      {
        params['org_id'] = this.current_org;
      }
      axios.post('device/'+ tempData.id+'/detach', params).then(res=>{
        _.remove(self.devices,function(d){
          return d.id == tempData.id;
        });
        self.devices.sort();
        this.dialogDetachVisible = false;
      }).catch(err=>{
        console.error(err);
      });
    },
    attachData(){
      this.load(this.current_org, this.listQuery);
    },
    updateData(){
      var self = this;
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, self.temp)

          axios.put('device/'+tempData.id, tempData).then((res) => {
            for (const v of self.devices) {
              if (v.id === res.data.id) {
                const index = self.devices.indexOf(v)
                self.devices.splice(index, 1, res.data)
                break
              }
            }
            self.dialogFormVisible = false
            self.$notify({
              title: self.$t('success.title'),
              message: self.$t('success.deviceRefresh'),
              type: 'success',
              duration: 2000
            })
          }).catch(err=>{
            console.error(err);

          })
        }
      })
    },
    onOrgChanged: function(org){
      this.current_org = org;
      this.load(this.current_org, this.listQuery);
    },
    load: function(current_org, query){
      var self = this;
      var path = 'devices';
      var params_q = {
        page: query.page,
        limit: query.limit,
      }
      if(current_org>0){
        params_q['org_id'] = current_org;
      }


      this.listLoading = true;
      axios.get(path,{params: params_q}).then(function (res) {
        self.devices = res.data.data;
        self.total = res.data.meta.pagination.total;
        }).catch(err=>{
        console.error(err)
        self.error = { title: self.$t('error.title'), message: self.$t('error.default') }
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = self.$t('error.auth')
            break
          case 500:
            self.error.message = self.$t('error.service')
            break
        }
        Message.error(self.error);
      });
      this.listLoading = false;
    }
  }
}
</script>
