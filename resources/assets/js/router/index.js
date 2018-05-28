import VueRouter from 'vue-router';
import Config from '../config/index';
import routers from './routes';





const router = new VueRouter({
    // 是否开启History模式的路由,默认开发环境开启,生产环境不开启。如果生产环境的服务端没有进行相关配置,请慎用
    history: Config.env != 'production',
    //history: false,
    routes:routers

});



export default router;
