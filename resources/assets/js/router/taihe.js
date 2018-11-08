import VueRouter from 'vue-router';
import Config from '../config/index';

const routers = [
  {
    path: '/devices/map',
    name: 'main-devices-map',
    meta:{ title:"设备地图",requireAuth: false},
    component: require('../views/main/taihe/device-map-taihe.vue')
  },
  {
    path: '/device/:id',
    name: 'main-device',
    meta: { title:"设备信息",requireAuth: false },
    component: require('../views/main/taihe/device-taihe.vue'),
  },
  {
    path:'/',
    name:'main',
    redirect: '/devices/map'
  },
  { path: '/404', component: require('../views/errorPage/404'), hidden: true },
  { path: '/401', component: require('../views/errorPage/401'), hidden: true },
  //{ path: '*', redirect: '/404', hidden: true }

]




const router = new VueRouter({
    // 是否开启History模式的路由,默认开发环境开启,生产环境不开启。如果生产环境的服务端没有进行相关配置,请慎用
    history: Config.env != 'production',
    //history: false,
    routes:routers

});



export default router;
