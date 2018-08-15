
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueHighcharts from 'vue-highcharts';
import highcharts from 'highcharts';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import router from './router/index';
import store from './store/index';
import Config from './config/index';
import App from './App.vue';
import ElementUI from 'element-ui';
import i18n from './lang' // Internationalization
import BaiduMap from 'vue-baidu-map';
import './icons' // icon
import VueImg from 'v-img';
import VueTimeago from 'vue-timeago'


import 'element-ui/lib/theme-chalk/index.css';
import './styles/index.scss'; // global css
import './assets/css/font-awesome.min.css';
import './assets/css/style.css';


Vue.use(VueTimeago, {
  locale: undefined,
  locales: { 'zh-CN': require('date-fns/locale/zh_cn'), }
})

var VueCookie = require('vue-cookie');
highcharts.setOptions({
  global: {
    useUTC: false
  }
});
Vue.prototype.$Config = Config;
Vue.use(Vuex);
Vue.use(VueCookie);
Vue.use(VueImg);
Vue.use(VueRouter);
Vue.use(VueHighcharts);
Vue.use(ElementUI, {
  size: 'medium', // set element-ui default size
  i18n: (key, value) => i18n.t(key, value)
});

// vue baidu map
Vue.use(BaiduMap, {
  // ak 是在百度地图开发者平台申请的密钥 详见 http://lbsyun.baidu.com/apiconsole/key */
  ak: 'upgAVMAni6rNgp1bM0xXU6LbCEg1lwrn'
})
// Vuex定义

Vue.prototype.$_has = function(perms_own, perms_need){
  let res = false;
  if(_.isArray(perms_need)){
    _.each(perms_need, function(perm_need){
      res|=_.isObject(_.findLast(perms_own, function(p){
        return p.name == perm_need;
      }));
    })
  }

  if(_.isString(perms_need)){
    res|=_.isObject(_.findLast(perms_own, function(p){
      return p.name == perms_need;
    }));
  }

  return res;

}

//router.map(Routers);

router.beforeEach((to, from, next) => {
    // 还原滚动条
    window.scrollTo(0, 0);
    // Auth验证
    if(to.meta.requireAuth) {  // 判断该路由是否需要登录权限
        if (store.state.currentUser.AccessToken) {  // 通过vuex state获取当前的token是否存在
            next();
        }
        else {
            next({
                path: '/login',
                query: {redirect: to.fullPath}  // 将跳转的路由path作为参数，登录成功后跳转到该路由
            })
        }
    }
    else {
        next();
    }
});


const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    render: h=>h(App)

});
