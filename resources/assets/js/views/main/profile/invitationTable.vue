<template>
  <div>
    <el-table :data="invitations" :key='tableKey' border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" :label="$t('profile.invitedOwner')" width="100px">
        <template slot-scope="scope">
          <span>{{scope.row.owner.name}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('profile.invitedUser')" width="100px">
        <template slot-scope="scope">
          <span>{{scope.row.user.name}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('profile.invitedRole')" width="100px">
        <template slot-scope="scope">
          <span>{{scope.row.role.name}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" v-if="invitationable == 'devices'" :label="$t('profile.device')" width="100px">
        <template slot-scope="scope">
          <span>{{scope.row.device.name}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" v-if="invitationable == 'organizations'" :label="$t('profile.organization')" width="100px">
        <template slot-scope="scope">
          <span>{{scope.row.organization.name}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('profile.status')" width="100px">
        <template slot-scope="scope">
          <el-tag :type="statusType[scope.row.status]">{{scope.row.status}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('profile.operation')" width="165px">
        <template slot-scope="scope">
          <el-button v-if="canEditPass(scope.row)" type="primary" size="mini" @click="handlePass(scope.row)">{{$t('profile.pass')}}</el-button>
          <el-button v-if="canEditPass(scope.row)" type="success" size="mini" @click="handleUnpass(scope.row)">{{$t('profile.unpass')}}</el-button>
          <el-button v-if="canEditDel(scope.row)" size="mini" type="danger" @click="handleDel(scope.row)">{{$t('profile.delete')}}</el-button>
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
  props:{
    userable:{
      type: String,
      required: true
    },
    invitationable:{
      type: String,
      required: true
    }
  },
  data(){
    return {
      statusType: {unpass:'danger', pass:'success',pending:'primary'},
      invitations:[],
      tableKey:0,
      total:0,
      listQuery: {
        page: 1,
        limit: 2
      },
    };
  },
  created: function(){
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
    canEditPass: function(row){
      return this.userable == 'user' && row.status == 'pending';
    },
    canEditDel: function(row){
      return this.userable == 'owner' && row.status == 'pending';
    },
    handlePass: function(row){
      axios.post("invitation/"+row.id+"/pass").then(res=>{
        row.status = "pass";
      }).catch(error=>{
        console.error(error);
      })
    },
    handleUnpass: function(row){
      axios.post("invitation/"+row.id+"/unpass").then(res=>{
        row.status = "unpass";
      }).catch(error=>{
        console.error(error);
      })
    },
    handleDel: function(row){
      axios.delete("invitation/"+row.id).then(res=>{
        _.remove(this.invitations,function(inv){ return inv.id == row.id});
        this.invitations.sort();
      }).catch(error=>{
        console.error(error);
      })
    },
    load: function(query){
      let param_t = {"invitationable_type": this.invitationable}
      param_t = _.merge(param_t, query)
      let self = this;
      axios.get('invitations/'+this.userable, {params:param_t}).then(res=>{
        self.invitations = res.data.data;
        self.total = res.data.meta.pagination.total;
      }).catch(error=>{
        console.error(error);
      });
    }
  }

}
</script>
