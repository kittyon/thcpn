<template>
  <div class="main">
    <div class="header">
      <div class="logo">
        <span class="big">{{ $Config.name.siteName }}</span>
        <span class="min">{{ $Config.name.minSiteMame }}</span>
      </div>
      <span class="header-btn" @click="sidebarToggle">
        <i class="el-icon-menu"></i>
      </span>
      <div class="right">
        <el-dropdown trigger="click">
          <span class="header-btn">
               <i class="el-icon-setting"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <div style="padding: 10px;text-align: center;width: 420px">
              <el-switch
                @change="saveSwitchTabBarVal"
                v-model="switchTabBar"
                active-text="开启TabBar"
                inactive-text="关闭TabBar">
              </el-switch>
              <el-alert
                v-if="switchTabBar"
                style="margin-top: 10px"
                title="导航标签超过容器时,可在导航上滚动鼠标来移动标签"
                type="info"
                show-icon>
              </el-alert>
            </div>
          </el-dropdown-menu>
        </el-dropdown>

        <span class="header-btn">
            <el-badge :value="3" class="badge">
                    <i class="el-icon-bell"></i>
            </el-badge>
        </span>
        <el-dropdown>
          <span class="header-btn">
              {{user.name}}<i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item>个人中心</el-dropdown-item>
            <el-dropdown-item @click.native="logout">退出系统</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <div class="app">
      <div class="aside">
        <el-menu
          router
          background-color="#222d32"
          text-color="#fff"
          :default-active="$route.path" class="menu" @open="handleOpen" @close="handleClose"
          :collapse="isCollapse">
          <template v-for="(menu_v,menu_k) in menu">
            <el-submenu v-if="menu_v.children" :index="menu_k">
              <template slot="title">
                <i :class="menu_v.icon"></i>
                <span slot="title">{{ menu_v.name }}</span>
              </template>
              <el-menu-item v-for="(menuChildren_v,menuChildren_k) in menu_v.children"
                            :key="menuChildren_k"
                            :index="menuChildren_v.path">
                <i class="is-children fa fa-circle-o"></i>
                <span slot="title">{{ menuChildren_v.name }}</span>
              </el-menu-item>
            </el-submenu>
            <el-menu-item v-else :index="menu_v.path">
              <i :class="menu_v.icon"></i>
              <span slot="title">{{ menu_v.name }}</span>
            </el-menu-item>
          </template>
        </el-menu>
      </div>
      <div class="app-body">
        <NavBar v-if="switchTabBar"></NavBar>
        <div v-else style="margin-top: 50px;"></div>
        <div class="app-container">
          <router-view></router-view>
        </div>
        <EuiFooter></EuiFooter>
      </div>
    </div>
  </div>
</template>

<script>
  import EuiFooter from './Footer.vue';
  import NavBar from './NavBar.vue'
  import Menu from '../../menu/index';
  export default {
    data() {
      return {
        switchTabBar: false,
        siteName: this.$Config.name.siteName,
        isCollapse: false,
        menu: Menu,
        user: {}
      };
    },
    methods: {
      saveSwitchTabBarVal(v) {
        v ? localStorage.setItem('switchTabBar', v) : localStorage.removeItem('switchTabBar');
      },
      sidebarToggle(e) {
        e.preventDefault();
        if (this.isCollapse) {
          document.body.classList.remove('sidebar-hidden')
          this.siteName = this.$Config.name.siteName
          this.isCollapse = false;
        } else {
          document.body.classList.add('sidebar-hidden')
          this.isCollapse = true;
        }
      },
      logout() {
        this.$store.commit('logout');
        this.$router.push({path: '/login'});
      },
      handleOpen(key, keyPath) {
        //console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        //关闭菜单
      }
    },
    created: function(){
      this.$store.dispatch('getCurrentUser');

    },
    mounted: function () {
      this.switchTabBar = localStorage.getItem('switchTabBar') ? true : false;
      if (!this.isCollapse) {
        document.body.classList.remove('sidebar-hidden')
        this.siteName = this.$Config.name.siteName
      } else {
        document.body.classList.add('sidebar-hidden')
      }
      this.$store.dispatch('getCurrentUser');
      this.user = this.$store.getters.user;
    },
    components: {
      EuiFooter, NavBar
    },
  }
</script>
<style lang="less">
  .sidebar-hidden {
    .header {
      .logo {
        .big {
          display: none;
        }
        .min {
          display: block;
        }
        width: 64px;
      }
    }
    .main {
      .app-body {
        margin-left: 64px;
      }
    }
  }
  .main {
    display: flex;
    .el-menu:not(.el-menu--collapse) {
      width: 230px;
    }
    .app {
      width: 100%;
      background-color: #ecf0f5;
    }
    .aside {
      position: fixed;
      margin-top: 50px;
      min-height: calc(~'100vh - 50px');
      z-index: 10;
      background-color: #222d32;
    }
    .app-body {
      margin-left: 230px;
      -webkit-transition: margin-left 0.3s ease-in-out;
      transition: margin-left 0.3s ease-in-out;
    }
    .main-container {
      //margin-top: 50px;
      padding: 10px;
      min-height: calc(~'100vh - 135px');
    }
  }
  .header {
    width: 100%;
    position: fixed;
    display: flex;
    height: 50px;
    background-color: #3c8dbc;
    z-index: 10;
    .logo {
      .min {
        display: none;
      }
      width: 230px;
      height: 50px;
      text-align: center;
      line-height: 50px;
      color: #fff;
      background-color: #367fa9;
      -webkit-transition: width 0.35s;
      transition: width 0.3s ease-in-out;
    }
    .right {
      position: absolute;
      right: 0;
    }
    .header-btn {
      .el-badge__content {
        top: 14px;
        right: 7px;
        text-align: center;
        font-size: 9px;
        padding: 0 3px;
        background-color: #00a65a;
        color: #fff;
        border: none;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
      }
      overflow: hidden;
      height: 50px;
      display: inline-block;
      text-align: center;
      line-height: 50px;
      cursor: pointer;
      padding: 0 14px;
      color: #fff;
      &:hover {
        background-color: #367fa9
      }
    }
  }
  .menu {
    border-right: none;
  }
  .el-menu--vertical {
    min-width: 190px;
  }
</style>
