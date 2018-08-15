<template>
  <div>
    <el-table :data="notifications" :key='tableKey' v-loading="listLoading" element-loading-text="给我一点时间" border fit highlight-current-row>
      <el-table-column align="center" :label="$t('notification.title')" width="150">
        <template slot-scope="scope">
          <a :href="scope.row.action_url">{{ scope.row.title }}</a>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('notification.body')" width="200">
        <template slot-scope="scope">
          <p>
            {{ scope.row.body }}
          </p>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('notification.created')" width="100">
        <template slot-scope="scope">
          <small class="timestamp">
            <timeago :since="scope.row.created" :auto-update="30"></timeago>
          </small>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('notification.status')" width="100">
        <template slot-scope="scope">

          <el-tag v-if="scope.row.read_at" :type="success">{{$t('notification.readed')}}</el-tag>
          <el-tag v-else :type="primary">{{$t('notification.notReaded')}}</el-tag>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[2,20,30,50]" :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>
  </div>
</template>
<script>
export default{
  data(){
    return {
      total: 0,
      listLoading: false,
      tableKey: 2,
      notifications:[],
      listQuery: {
        page: 1,
        limit: 2
      },

    };
  },
  created(){
    this.load(this.listQuery);
  },
  methods:{
    handleSizeChange: function(val){
      this.listQuery.limit = val;
      this.load(this.listQuery);
    },
    handleCurrentChange: function(val){
      this.listQuery.page = val;
      this.load(this.listQuery);
    },
    load: function(query){
      this.listLoading = true;
      var params_q = {
        page: query.page,
        limit: query.limit,
      }
      var self = this;
      axios.get('notifications/all', {params: params_q}).then(res=>{
        self.notifications = res.data.data;
        self.total = res.data.meta.pagination.total;
        self.listLoading = false;
      }).catch(error=>{
        console.log(error);
        self.listLoading = false;
      })
    }
  }
}
</script>
