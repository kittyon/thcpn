<template>
  <div class="tab-container">
    <el-tabs style='margin-top:15px;' v-model="activeName" type="border-card">
        <el-tab-pane :label="$t('org.device')" name="device">
          <org-device v-if="org" :org="org"/>
        </el-tab-pane>
        <el-tab-pane :label="$t('org.people')" name="people">
          <org-people v-if="org" :org="org"/>
        </el-tab-pane>
        <el-tab-pane :label="$t('org.setting')" name="setting">
          <org-setting v-if="org" :org="org"/>
        </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import orgDevice from './org/device.vue';
import orgPeople from './org/people.vue';
import orgSetting from './org/setting.vue';
export default {
  name: 'org',
  components:{
    'org-device': orgDevice,
    'org-people': orgPeople,
    'org-setting': orgSetting
  },
  data(){
    return {
      org:{},
      activeName:"device"
    };
  },
  created: function(){
    var org_id = this.$route.params.id;
    var self = this;
    axios.get("organization/"+org_id).then(res=>{
      console.log(res)
      self.org = res.data;
    }).catch(error=>{
      console.error(error);
    });
  },
  methods:{

  }
}
</script>
