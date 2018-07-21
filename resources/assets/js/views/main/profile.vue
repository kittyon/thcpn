<template>
  <div>
    <el-tabs style='margin-top:15px;' v-model="activeName" type="border-card">
        <el-tab-pane :label="$t('profile.invitation')" name="invitation">
          <profile-invitation/>
        </el-tab-pane>
        <el-tab-pane :label="$t('profile.account')" name="account">
          <profile-account />
        </el-tab-pane>

    </el-tabs>

  </div>
</template>
<script>
import invitation from "./profile/invitation.vue"
import account from "./profile/account.vue"
export default{
  components:{
    "profile-invitation": invitation,
    "profile-account": account
  },
  data(){
    return {
        activeName: "invitation",
    }
  },
  methods:{

  }
}
</script>
<style>
</style>
