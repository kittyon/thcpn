<template>
  <div class="sub-navbar draft">
    <div v-if="orgs.length > 1">
      <el-select v-model="current_org">
        <el-option v-for="org in orgs" :value="org.id" :key="org.id" :label="org.name"></el-option>
      </el-select>
    <!--<button class="btn btn-primary btn-xs" @click="back()">
      <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> 返回</button>-->
    </div>
  </div>
</template>
<script>
  import {mapGetters} from "vuex";
  export default {
    data: function () {
      return {
        current_org: 0,
        orgs:[]
      }
    },
    computed:{
      ...mapGetters(
        {
          user:'user'
        })
    },
    watch: {
      current_org: function (newOrg, oldOrg) {
        console.log("org changed");
        this.$cookie.set('current_org',newOrg);
        this.$emit('orgChanged',newOrg);
        // location.href = '/eason';
      }
    },
    created: function () {
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
      // Cookie.set('currentCode', =='null' || (thc.user.codes[0]?thc.user.codes[0].id:null));
    }
  }
</script>
<style>
.sub-navbar {
  height: 50px;
  line-height: 50px;
  position: relative;
  width: 100%;
  text-align: right;
  padding-right: 20px;
  transition: 600ms ease position;
  background: linear-gradient(90deg, rgba(32, 182, 249, 1) 0%, rgba(32, 182, 249, 1) 0%, rgba(33, 120, 241, 1) 100%, rgba(33, 120, 241, 1) 100%);
  .subtitle {
    font-size: 20px;
    color: #fff;
  }
  &.draft {
    background: #d0d0d0;
  }
  &.deleted {
    background: #d0d0d0;
  }
}

</style>
