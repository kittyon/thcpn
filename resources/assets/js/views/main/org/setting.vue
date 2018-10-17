<template>
  <div>
    <el-form :model="org" label-width="80px">
      <el-form-item :label="$t('org.name')">
        <el-input v-model="org.name" style="max-width: 200px"></el-input>
      </el-form-item>
      <el-form-item :label="$t('org.description')">
        <el-input v-model="org.description" style="max-width: 200px"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="orgUpdate" type="success">{{$t('org.update')}}</el-button>
      </el-form-item>
    </el-form>
  </div>

</template>
<script>
export default{
  props:['org'],
  data(){
    return {
      isOrgEdited: false,
    }
  },
  created: function(){

  },
  methods:{
    orgUpdate:function(){
      let self = this;
      let param_t = {name: this.org.name, description: this.org.description}
      axios.put("organization/"+this.org.id, param_t).then(res=>{
        self.$notify({
          title: self.$t('success.title'),
          message: self.$t('success.refresh'),
          type: 'success',
          duration: 2000
        })
      }).catch(error=>{

      });
    },

  }
}
</script>
