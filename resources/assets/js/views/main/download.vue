<template>
  <div>
    <div class="sub-navbar">
        <span v-if="orgs.length > 1" class="sub-navbar-header" style="margin-right:15px; color: #fff;">{{$t('download.organization')}}</span>
        <el-select v-if="orgs.length > 1" class="sub-navbar-header" v-model="current_org">
          <el-option v-for="org in orgs" :value="org.id" :key="org.id" :label="org.name"></el-option>
        </el-select>
    </div>
    <el-form :model="options" :rules="rules" ref="options" label-width="80px">
      <el-form-item :label="$t('download.device')" prop="device">
        <el-select
          v-model="options.devices"
          multiple
          collapse-tags
          style="margin-left: 20px;"
          placeholder="$t('download.devicePlaceholder')">
          <el-option
            v-for="item in devices"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('download.date')" prop="date">
        <el-date-picker
          v-model="options.dates"
          type="datetimerange"
          :range-separator="$t('download.to')"
          :start-placeholder="$t('download.startDate')"
          :end-placeholder="$t('download.endDate')">
        </el-date-picker>
      </el-form-item>
      <el-form-item :label="$t('download.content')" prop="contents">
        <el-checkbox-group v-model="options.contents" size="medium">
          <el-checkbox-button v-for="dt in dataTypes" :label="dt" :key="dt">{{$t('download.'+dt)}}</el-checkbox-button>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item>
        <el-button @click="handleDownloadRaw" type="success">{{$t('download.execute')}}</el-button>
      </el-form-item>
    </el-form>
    <el-card>
      <el-table :key='tableKey' :data="downloadJobs" v-loading="listLoading" element-loading-text="给我一点时间" border fit highlight-current-row>
        <el-table-column align="center" :label="$t('download.id')" width="65">
          <template slot-scope="scope">
            <span>{{scope.row.id}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('download.created_at')" width="100">
          <template slot-scope="scope">
            <span>{{scope.row.created_at}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('download.date')" width="200">
          <template slot-scope="scope">
            <span>{{scope.row.options.dates[0]}}</span>
            <span>{{$t('download.to')}}</span>
            <span>{{scope.row.options.dates[1]}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('download.status')" width="200">
          <template slot-scope="scope">
            <span>{{$t('download.'+scope.row.status)}}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" :label="$t('download.operation')" width="200">
          <template slot-scope="scope">
            <el-button v-if="canDownload(scop.row.status)" @click="handleDownload(scop.row.url)">{{$t('download.execute')}}</el-button>
            <el-button v-if="canDel(scop.row.status)" @click="handleDel(scope.row)">{{$t('download.del')}}</el-button>
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
export default{
  created:function(){
    this.getOrg();
    this.getDevice(org);
  },
  data(){
    const dataTypes = ['image', 'data'];
    var validateDevice = (rule, value, callback)=>{
      if(value.length == 0){
        callback(new Error('请选择下载设备'));
      }
      else{
        callback();
      }
    };
    var validateDate = (rule, value, callback)=>{
      if(value.length < 2){
        callback(new Error('请输入下载日期范围'));
      }
      else if(moment(query.dates[1]).diff(moment(query.dates[0]), "days")>30){
        callback(new Error('下载间隔大于30天,请重新选择!'));
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
      downloadJobs:[],
      orgs:[],
      current_org:0,
      rules:{
        devices:[
          {validator: validateDevice, trigger: 'blur'}
        ],
        dates:[
          { required: true, message: '请输入下载日期', trigger: 'blur' },
        ],
        contents:[
          { required: true, message: '请选择下载内容', trigger: 'blur' },
          { validator: validateDate, trigger: 'blur'}
        ],
        listLoading: false
      }
    }
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
      axios.get('download', {params:{query}}).then(res=>{
        self.downloadJobs = res.data.data;
        self.total = res.data.meta.pagination.total;
      }).catch(error=>{
        console.error(error);
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
      this.listLoading = true;
      axios.get(path,{params: params_q}).then(function (res) {
        self.devices = res.data.data;
        }).catch(err=>{
        console.error(err)
        self.error = { title: '发生错误', message: '出现异常，请稍后再试！' }
        switch (err.response && err.response.status) {
          case 401:
            self.error.message = '用户名或密码错误！'
            break
          case 500:
            self.error.message = '服务器内部异常，请稍后再试！'
            break
        }
        Message.error(self.error);
      });
    },
    handleDownloadRaw: function(){
      var query = this.options;
      let self = this;
      axios.post('download', query).then(res=>{
        self.downloadJobs.push(res);
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
        _.remove(self.downloadJobs,function(val){
          return row.id == val.id;
        });
        self.downloadJobs.sort();
      });
    },
    canDownload: function(status){
      return status == "completed";
    },
    canDel: function(status){
      return status == "completed";
    }
  }
}
</script>
