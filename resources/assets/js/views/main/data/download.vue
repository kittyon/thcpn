<template>
  <div>
    <div class="sub-navbar draft">
        <span v-if="orgs.length > 1" class="sub-navbar-header" style="margin-right:15px; color: #fff;">{{$t('data.organization')}}</span>
        <el-select v-if="orgs.length > 1" class="sub-navbar-header" v-model="current_org">
          <el-option v-for="org in orgs" :value="org.id" :key="org.id" :label="org.name"></el-option>
        </el-select>
        <el-button type="primary" @click="refresh">{{$t('data.refresh')}}</el-button>
    </div>
    <el-form :model="options" :rules="rules" ref="options" label-width="80px" style="margin-top:20px">
      <el-form-item :label="$t('data.device')" prop="devices">
        <el-select
          v-model="options.devices"
          multiple
          collapse-tags
          :placeholder="$t('data.devicePlaceholder')">
          <el-option
            v-for="item in devices"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('data.date')" prop="dates">
        <el-date-picker
          v-model="options.dates"
          type="datetimerange"
          :range-separator="$t('data.to')"
          :start-placeholder="$t('data.startDate')"
          :end-placeholder="$t('data.endDate')">
        </el-date-picker>
      </el-form-item>
      <el-form-item :label="$t('data.content')" prop="contents">
        <el-checkbox-group v-model="options.contents" size="medium">
          <el-checkbox-button v-for="dt in dataTypes" :label="dt" :key="dt">{{$t('data.'+dt)}}</el-checkbox-button>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item>
        <el-button @click="handleDownloadRaw" type="success">{{$t('data.execute')}}</el-button>
      </el-form-item>
    </el-form>
    <el-card>
      <el-table :key='tableKey' :data="jobs" v-loading="listLoading" :element-loading-text="$t('table.loading')" border fit highlight-current-row style="width: 100%">
        <el-table-column align="center" :label="$t('data.id')" width="65">
          <template slot-scope="scope">
            <span>{{scope.row.id}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('data.created_at')" width="100">
          <template slot-scope="scope">
            <timeago :since="scope.row.created_at.date" :auto-update="30"></timeago>

          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('data.device')" width="100">
          <template slot-scope="scope">
            <el-tag :key="dev.id" v-for="dev in scope.row.options.devices">{{dev.name}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('data.content')" width="100">
          <template slot-scope="scope">
            <el-tag :key="ct" v-for="ct in scope.row.options.contents">{{$t('data.'+ct)}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('data.date')" width="200">
          <template slot-scope="scope">
            <span>{{scope.row.options.dates[0]}}</span>
            <span>{{$t('data.to')}}</span>
            <span>{{scope.row.options.dates[1]}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('data.status')" width="200">
          <template slot-scope="scope">
            <span>{{$t('data.'+scope.row.status)}}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" :label="$t('data.operation')" width="200">
          <template slot-scope="scope">
            <el-button v-if="canDownload(scope.row.status)" @click="handleDownload(scope.row.url)">{{$t('data.execute')}}</el-button>
            <el-button v-if="canDel(scope.row.status)" @click="handleDel(scope.row)">{{$t('data.del')}}</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="pagination-container">
        <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[2,20,30,50]" :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
        </el-pagination>
      </div>
    </el-card>
  </div>
</template>
<script>
import api from '../../../api';
import {mapState} from "vuex";
export default{
  created:function(){
    this.getOrg();
    this.getDevice(this.current_org);
    this.getJob(this.listQuery);
  },
  watch:{
    current_org: function(val, oldval){
      this.$cookie.set('current_org',val);
      this.getDevice(val);
    }
  },
  data(){
    const dataTypes = ['image', 'data'];
    var validateDevice = (rule, value, callback)=>{
      if(value.length == 0){
        callback(new Error($t('error.device')));
      }
      else{
        callback();
      }
    };
    var validateDate = (rule, value, callback)=>{
      if(value.length < 2){
        callback(new Error($t('error.date')));
      }
      else if(moment(value[1]).diff(moment(value[0]), "days")>30){
        callback(new Error($t('error.dateRange')));
      }
      else{
        callback();
      }
    };

    return {
      listQuery: {
        page: 1,
        limit: 2
      },
      total: 0,
      devices:[],
      dataTypes: dataTypes,
      contents:[],
      options: {
        devices:[],
        dates:[],
        contents:[]
      },
      tableKey: 0,
      jobs:[],
      orgs:[],
      current_org:0,
      rules:{
        devices:[
          { required: true, message: $t('error.device'), trigger: 'blur' },
          {validator: validateDevice, trigger: 'blur'}
        ],
        dates:[
          { required: true, message: $t('error.date'), trigger: 'blur' },
          { validator: validateDate, trigger: 'blur'}
        ],
        contents:[
          { required: true, message: $t('error.content'), trigger: 'blur' },

        ],

      },
      listLoading: false
    }
  },
  computed:{
    ...mapState(
      {
        user: state=> state.user.Current
      })
  },
  methods:{
    handleSizeChange: function(val){
      this.listQuery.limit = val;
      this.getJob(this.listQuery);
    },
    handleCurrentChange: function(val){
      this.listQuery.page = val;
      this.getJob(this.listQuery);
    },
    getJob: function(query){
      var self = this;
      this.listLoading = true;
      var params_q ={
        page: query.page,
        limit: query.limit
      }
      axios.get('downloads', {params:params_q}).then(res=>{
        self.jobs = res.data.data;
        self.total = res.data.meta.pagination.total;
        this.listLoading = false;
      }).catch(error=>{
        console.error(error);
        this.listLoading = false;
      })
    },
    getOrg: function(){
      var self = this;
      this.current_org = api.get_current_org();

      var self = this;
      self.orgs.push({id:0, name: self.user.name});
      axios.get('organizations').then(res=>{
        self.orgs = _.concat(self.orgs, res.data.data);
      });
      if (isNaN(parseInt(this.$cookie.get('current_org')))) {
        this.$cookie.set('current_org',0);
        this.current_org = 0;
      }
      else{
        this.current_org = parseInt(this.$cookie.get('current_org'));
      }
    },
    getDevice(current_org){
      var self = this;
      var path = 'devices';
      var params_q = {
      }
      if(current_org>0){
        params_q['org_id'] = current_org;
      }

      axios.get(path,{params: params_q}).then(function (res) {
        self.devices = res.data.data;

        }).catch(err=>{
        console.error(err)
        self.error = { title: $t('error.title'), message: $t('error.default')}
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = $t('error.auth')
            break
          case 500:
            self.error.message = $t('error.service')
            break
        }
      });
    },
    handleDownloadRaw: function(){
      var query = this.options;
      let self = this;
      axios.post('download', query).then(res=>{
        self.jobs.push(res);
      }).catch(error=>{
        console.error(error);
      })
    },
    handleDownload: function(rurl){
      if (!rurl) {
          return;
        }
        var url = 'http://thc-platfrom-storage.b0.upaiyun.com' + rurl;
        console.log(url);
        var a = $("<a>")
          .attr("href", url)
          .appendTo("body");
        a[0].click();
        a.remove();
    },
    handleDel: function(row){
      let self = this;
      axios.delete('download/'+row.id).then(res=>{
        _.remove(self.jobs,function(val){
          return row.id == val.id;
        });
        self.jobs.sort();
      });
    },
    canDownload: function(status){
      return status == "completed";
    },
    canDel: function(status){
      return status == "completed";
    },
    refresh: function(){
      this.getJob(this.listQuery);
    }
  }
}
</script>
