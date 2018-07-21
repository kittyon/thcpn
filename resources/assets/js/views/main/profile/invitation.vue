<template>
  <div>
    <el-card class="box-card" >
      <el-tabs v-model="activeName" tab-position="left">
        <el-tab-pane :label="$t('profile.owner_dev_invitation')"
         name="owner_dev_invitation">
         <invitation-table userable="owner" invitationable="devices"/>
       </el-tab-pane>

        <el-tab-pane :label="$t('profile.user_dev_invitation')"

         name="user_dev_invitation">
         <invitation-table  userable="user" invitationable="devices"/>
        </el-tab-pane>

        <el-tab-pane :label="$t('profile.owner_org_invitation')"
          name="owner_org_invitation">
          <invitation-table  userable="owner" invitationable="organizations"/>
        </el-tab-pane>

        <el-tab-pane :label="$t('profile.user_org_invitation')"
         name="user_org_invitation">
         <invitation-table  userable="user" invitationable="organizations"/>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>
<script>
import InvitationTable from './invitationTable.vue'
export default{
  components:{
    'invitation-table': InvitationTable
  },
  data(){
      return {
        activeName:"owner_org_invitation",
      };
  },
  created: function(){
  },
  methods:{

  }

}
</script>
