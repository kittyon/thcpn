<template>
  <div>
    <div class="sub-navbar draft">
      <el-button @click="handleInvite">{{$t('org.inviteUser')}}</el-button>
    </div>
    <el-table :key='tableKey' :data="peoples" v-loading="listLoading" element-loading-text="给我一点时间" border fit highlight-current-row
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

      <el-table-column align="center" :label="$t('table.actions')" width="230" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button v-if="canEdit" size="mini" type="danger" @click="handleDetach(scope.row)">{{$t('table.detach')}}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="listQuery.page" :page-sizes="[2,20,30,50]" :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>

    <el-dialog :title="$t('table.create')" :visible.sync="dialogInvitVisible">
      <span>{{$t('org.inviteTitle')}}</span>
      <!--<el-autocomplete :fetch-suggestions="querySearch" @select="handleSelect" v-model="iUser" :trigger-on-focus="false">
        <el-select v-model="selectType" slot="prepend" placeholder="请选择">
          <el-option label="用户名" value="name"></el-option>
          <el-option label="email" value="email"></el-option>
          <el-option label="用户手机" value="phone"></el-option>
        </el-select>
        <template slot-scope="{ item }">
          <div class="name">{{ item.name }}</div>
        </template>
      </el-autocomplete>-->
      <el-select v-model="iUser" remote filterable reserve-keyword :placeholder="$t('org.input')"
       :remote-method="querySearch"
       :loading="loading">
       <el-select v-model="selectType" slot="prepend" :placeholder="$t('org.inviteType')">
         <el-option :label="$t('org.inviteTypeName')" value="name"></el-option>
         <el-option :label="$t('org.inviteTypeEmail')" value="email"></el-option>
         <el-option :label="$t('org.inviteTypePhone')" value="phone"></el-option>
       </el-select>
       <el-option v-for="item in invitingUsers" :key="item.id" :label="item.name" :value="item"></el-option>

      </el-select>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogDetachVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="inviteUser">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('table.detach')" :visible.sync="dialogDetachVisible">
      <span>{{$t('org.removePeopleTitle')}}</span>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogDetachVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="detachUser">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>
</div>
</template>
<script>
import api from '../../../api';
import {Message} from 'element-ui'
export default{
  props:['org'],
  data(){
    return {
      current_org: 0,
      peoples: [],
      invitingUsers:[],
      loading: false,
      listLoading: false,
      dialogInvitVisible: false,
      dialogDetachVisible: false,
      selectType: 'name',
      iUser: null,
      dUser: null,
      tableKey: 0,
      listQuery: {
        page: 1,
        limit: 2
      },
      error:"",
      total: 0,

    };
  },
  created(){
    var self = this;
    this.current_org = this.org.id;
    if(this.current_org){
      this.load(this.current_org, this.listQuery);
    }

  },
  watch:{
    org:{
      handler: function(val, oldval){
        this.current_org = val.id;
        if(this.current_org){
          this.load(this.current_org, this.listQuery);
        }
      }
    }
  },
  methods:{
    handleDetach: function(user){
      this.dUser = user;
      this.dialogDetachVisible = true;
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
    detachUser: function(){
      var param_t = {'user_id': this.dUser.id};
      let self = this;
      console.log(param_t)
      axios.post('organization/'+ this.current_org+'/detach', param_t).then(res=>{
        self.dialogDetachVisible = false;
        _.remove(self.peoples,function(p){return p.id == self.dUser.id});
        self.peoples.sort();
        self.$notify({
          title: self.$t('success.title'),
          message: self.$t('success.removePeople'),
          type: 'success',
          duration: 2000
        })
      }).catch(err=>{
        console.error(err);
      });
    },
    handleInvite: function(){
      this.dialogInvitVisible = true;
    },
    inviteUser: function(){
      let self = this;
      if(this.iUser){
        var param_t = {'org_id': this.current_org, 'user_id': this.iUser.id};
        axios.post('user/invite', param_t).then(res=>{
          self.$notify({
            title: self.$t('success.title'),
            message: self.$t('success.invitePeople'),
            type: 'success',
            duration: 2000
          });
          self.dialogInvitVisible = false;
        });
      }

    },
    canEdit: function(){

    },
    handleSelect: function(item){

    },
    querySearch(queryString) {
        var param_t = {'colName': this.selectType, 'content': queryString};
        let self = this;
        self.loading = true;
        axios.get('user/search', {params:param_t}).then(res=>{
          console.log(res.data.data);
          self.invitingUsers = res.data.data;
          self.loading = false;
        }).catch(err=>{
        console.error(err)
        self.error = { title: self.$t('error.title'), message: self.$t('error.default') }

        self.loading = false;
        Message.error(self.error);
      });
      },

    load: function(org_id, query){
      var self = this;
      var path = 'organization/'+org_id+'/users';
      var params_q = {
        page: query.page,
        limit: query.limit,
      }
      this.listLoading = true;
      axios.get(path,{params: params_q}).then(function (res) {
        self.peoples = res.data.data;
        self.total = res.data.meta.pagination.total;
        }).catch(err=>{
        console.error(err)
        self.error = { title: self.$t('error.title'), message: self.$t('error.default') }

        Message.error(this.error);
      });
      this.listLoading = false;
    }
  }
}
</script>
