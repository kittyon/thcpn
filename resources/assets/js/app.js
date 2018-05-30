
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import router from './router/index';
import store from './store/index';

import App from './App.vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(ElementUI);

// Vuex定义


//router.map(Routers);

router.beforeEach((to, from, next) => {
    // 还原滚动条
    window.scrollTo(0, 0);
    // Auth验证
    if (to.meta.requireAuth) {  // 判断该路由是否需要登录权限
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
    render: h=>h(App)

});
