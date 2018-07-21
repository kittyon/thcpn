<template>
  <div>
    <div class="sub-navbar">
        <el-button type="success" size="mini" @click="handleAddOrg()">{{$t('org.add')}}</el-button>
      <!--<button class="btn btn-primary btn-xs" @click="back()">
        <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> 返回</button>-->
    </div>
    <el-table :key='tableKey' :row-style="showRow" :data="formatData" v-loading="listLoading" element-loading-text="给我一点时间" border fit highlight-current-row
      style="width: 100%">
      <el-table-column min-width="65px">
        <template slot-scope="scope">
          <span v-for="space in scope.row._level" class="ms-tree-space" :key="space"></span>
          <span class="tree-ctrl" v-if="iconShow(scope.row)" @click="toggleExpanded(scope.$index,scope.row.id)">
            <i v-if="!scope.row._expanded" class="el-icon-plus"></i>
            <i v-else class="el-icon-minus"></i>
          </span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('table.id')" width="65">
        <template slot-scope="scope">
          <span>{{scope.row.id}}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="150px" :label="$t('table.name')">
        <template slot-scope="scope">
          <span class="link-type">{{scope.row.name}}</span>
        </template>
      </el-table-column>
      <el-table-column width="110px" align="center" :label="$t('table.description')">
        <template slot-scope="scope">
          <span>{{scope.row.description}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('table.actions')" width="230" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button v-if="canEdit(scope.row)" type="primary" size="mini" @click="handleDetails(scope.row)">{{$t('org.details')}}</el-button>
          <el-button v-if="canEdit(scope.row)" type="primary" size="mini" @click="handleAddSubOrg(scope.row.id)">{{$t('org.addsub')}}</el-button>
          <el-button v-if="canDetach(scope.row)" size="mini" type="danger" @click="handleDetach(scope.row)">{{$t('org.detach')}}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="$t('table.create')" :visible.sync="dialogFormVisible">
      <el-form :rules="rules" ref="dataForm" :model="tmp" label-position="left" label-width="70px" style='width: 50%, margin-left:50px;'>
        <el-form-item :label="$t('table.name')" prop="name">
          <el-input v-model="tmp.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('table.description')" prop="description">
          <el-input v-model="tmp.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="createOrg">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>
    <el-dialog :title="$t('table.detach')" :visible.sync="dialogDetachVisible">
      <span>确定退出该组织（{{tmp.name}}）关注？</span>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogDetachVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="detachData">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
export default{
  name: 'org-list',
  data(){
    return {
      listLoading: false,
      error: null,
      orgs: [],
      user: {},
      tableKey: 0,
      tmpOrg: null,
      expandAll:false,
      formatData:[],
      tmp: {
        organization_id: undefined,
        name:'',
        description:''
      },
      rules: {
        name: [{ required: true, message: 'name is required', trigger: 'change' }],
        description: [{ required: true, message: 'description is required', trigger: 'change' }],
      },
      dialogStatus: '',
      dialogFormVisible: false,
      dialogDetachVisible: false,
    };
  },
  /*computed: {
    // 格式化数据源
    formatData: function() {
      let tmp
      if (!Array.isArray(this.orgs)) {
        tmp = [this.orgs]
      } else {
        tmp = this.orgs
      }
      const func = this.treeToArray
      const args = [tmp, this.expandAll]
      console.log("computed formatData");
      return func.apply(null, args)
    }
  },*/
  watch:{
    orgs: {
      handler: function(val, oldVal){
        let tmp
        if (!Array.isArray(this.orgs)) {
          tmp = [this.orgs]
        } else {
          tmp = this.orgs
        }
        const func = this.treeToArray
        const args = [tmp, this.expandAll]
        this.formatData = func.apply(null, args);
      },
      deep: true
    }
  },
  created:function(){
    this.user = this.$store.getters.user;
    var self = this;
    axios.get('organizations').then(res=>{
      self.orgs = res.data.data;
    }).catch(error=>{
      console.log(error);
    })
  },
  methods:{
    canEdit(row){
      return this.$_has(row.perms,'org_w');
    },
    canDetach(row){
      return this.$_has(row.perms,'org_w') && row._level == 1;
    },
    handleDetails(row){
      this.$router.push({path:"/org/"+row.id});
    },
    handleDetach(row){
      this.tmp = _.assign({}, row) // copy obj
      this.dialogDetachVisible = true;
    },
    detachData(){
      const tmpData = _.assign({}, this.tmp);
      var param_t = {'user_id': this.user.Id};
      axios.post('organization/'+ tmpData.id+'/detach', param_t).then(res=>{
        this.dialogDetachVisible = false;
        this.$notify({
          title: '成功',
          message: '退出组织成功',
          type: 'success',
          duration: 2000
        })
      }).catch(err=>{
        console.error(err);
      });
    },
    handleAddOrg: function(){
      this.tmp = {name: '', description:'',organization_id: null};
      this.dialogFormVisible = true;
    },
    handleAddSubOrg: function(id){
      this.tmp = {name: '', description: '', organization_id: id};
      this.dialogFormVisible = true;
    },
    createOrg: function(){
      var uri = '/organization';
      let param = this.tmp;
      let self = this;
      axios.post(uri, param).then((res)=>{
        this.dialogFormVisible = false;
        if(this.tmp.organization_id == null){
            this.orgs.push(res.data);
        }
        else{
          let pOrg = this.getOrg(this.tmp.organization_id);
          res.data.perms = pOrg.perms;
          (pOrg['children']||(pOrg['children']=[])).push(res.data);
          pOrg['children_count']+=1;
          let tmp
          if (!Array.isArray(self.orgs)) {
            tmp = [self.orgs]
          } else {
            tmp = self.orgs
          }
          const func = self.treeToArray
          const args = [tmp, self.expandAll]
          self.formatData = func.apply(null, args);
        }

        this.$notify({
          title: '成功',
          message: '组织创建成功',
          type: 'success',
          duration: 2000
        });
      }).catch(err=>{
        console.error(err);
      });
    },
    showRow: function(row) {
      const show = (row.row.parent ? (row.row.parent._expanded && row.row.parent._show) : true)
      row.row._show = show
      return show ? 'animation:treeTableShow 1s;-webkit-animation:treeTableShow 1s;' : 'display:none;'
    },
    //
    getOrg(id){
      return _.find(this.formatData, {'id': id});
    },
    // 切换下级是否展开
    toggleExpanded: function(index,id) {
      let row = this.getOrg(id);
      let self = this;

      if(!('children' in row) || (row['children']||(row['children']=[])).length==0){
        axios.get('/organization/'+id+'/children').then(function(res){
          row.children = res.data.data;
          let tmp
          if (!Array.isArray(self.orgs)) {
            tmp = [self.orgs]
          } else {
            tmp = self.orgs
          }
          const func = self.treeToArray
          const args = [tmp, self.expandAll]
          self.formatData = func.apply(null, args);

        })
      }
      this.formatData[index]._expanded = !this.formatData[index]._expanded;

    },
    // 图标显示
    iconShow(row) {
      return (this.canEdit(row) && (row.children_count>0));
    },
    treeToArray(data, expandAll, parent = null, level = null) {
      let tmp = []
      let self = this
      Array.from(data).forEach(function(record) {
        if (record._expanded === undefined) {
          Vue.set(record, '_expanded', expandAll)
        }
        let _level = 1
        if (level !== undefined && level !== null) {
          _level = level + 1
        }
        Vue.set(record, '_level', _level)
        // 如果有父元素
        if (parent) {
          Vue.set(record, 'parent', parent)
        }
        tmp.push(record)
        if (record.children && record.children.length > 0) {

          let children = self.treeToArray(record.children, expandAll, record, _level)
          console.log(children)
          tmp = tmp.concat(children)
        }
      })
      return tmp
    }
  }
}
</script>
<style lang="scss" rel="stylesheet/scss" scoped>

  $color-blue: #2196F3;
  $space-width: 18px;
  .ms-tree-space {
    position: relative;
    top: 1px;
    display: inline-block;
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    width: $space-width;
    height: 14px;
    &::before {
      content: ""
    }
  }
  .processContainer{
    width: 100%;
    height: 100%;
  }
  table td {
    line-height: 26px;
  }

  .tree-ctrl{
    position: relative;
    cursor: pointer;
    color: $color-blue;
    margin-left: -$space-width;
  }
</style>
