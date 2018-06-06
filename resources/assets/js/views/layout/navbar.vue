<template>
    <nav class="nav-bar">
        <ScrollPane class="nav-bar-scroll">
            <router-link  :to="v.path" class="nav-bar-tag" v-for="(v,i) in nav"
                         :key="i.path" :class="$route.path == v.path ? ' active':''">
                <i class="point"></i>
                {{ v.title }}
                <span v-show="nav.length != 1" class='el-icon-close' @click.prevent.stop='closeSelectedTag(i)'></span>
            </router-link>
        </ScrollPane>
    </nav>
</template>

<script>
  import ScrollPane from '../../components/ScrollPane/Index.vue'
  export default {
    data() {
      return {
        nav: [],
      };
    },
    components: {
      ScrollPane
    },
    watch: {
      '$route': {
        handler: function () {
          let index = this.$Func.hasValInArrayObj(this.nav, 'path', this.$route.path);
          if(index != -1)return;
          this.nav.push({
            path: this.$route.path,
            title: this.$route.meta.title,
          });

        },
        // 深度观察
        deep: true
      }
    },
    methods: {
      closeSelectedTag(i){
        let nav = this.nav;
        let thisPath = nav[i].path;
        nav.splice(i,1);
        if(thisPath == this.$route.path){
          this.$router.push(nav[(nav.length-1)].path);
        }

      }
    },
    mounted: function () {
      this.nav.push({
        path: this.$route.path,
        title: this.$route.meta.title,
      });
    }
  }
</script>
<style lang="less" scoped="">
    .nav-bar {
        margin-top: 50px;
        height: 38px;
        background: #fff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .12), 0 0 3px 0 rgba(0, 0, 0, .04);

        .nav-bar-tag {
            height: 26px;
            color: #495060;
            margin: 6px 2px;
            padding-left: 6px;
            padding-right: 6px;
            font-size: 12px;
            line-height: 26px;
            border: 1px solid #d8d8d8;
            border-radius: 2px;
            display: inline-block;
            &:hover {
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 0 3px 0 rgba(0, 0, 0, 0.04);
                //border-color: #3d8dbc;
            }
            .point {
                display: none;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                margin: auto 0;
                position: relative;
                background: #fff;
            }
            .el-icon-close {
                margin: 0 4px;
                border-radius: 50%;
                &:hover {
                    background: #fa5555;
                    color: #fff;
                }
            }
        }
        .active {
            background: #3d8dbc;
            color: #fff;
            .point{
                display: inline-block;
            }
        }
        .scroll-container {
            flex: 1;
            height: 100%;
        }
    }
</style>
