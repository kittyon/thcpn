import mainRouters from './main-routers'

const routers = [
  {
    path:'/login',
    name: 'login',
    meta:{
      title: "登录",
      requireAuth: false,
    },
    component:require('../views/auth/login.vue')
  },
  {
    path:'/register',
    name:'register',
    redirect: '/register/email'
    //name:'register',
    //meta:{
    //  title: "注册",
    //  requireAuth:false,
    //},
    //component:require('../views/auth/register.vue'),
  },
  {
    path:'/register/email',
    name:'register-email',
    meta:{
      title: "注册-邮箱",
      requireAuth:false,
    },
    component:require('../views/auth/register-email.vue'),
  },
  {
    path:'/register/phone',
    name:'register-phone',
    meta:{
      title: "注册-电话",
      requireAuth:false,
    },
    component:require('../views/auth/register-phone.vue'),
  },
  {
    path:'/password/reset',
    name: 'password-reset',
    meta:{
      title:"密码重置",
      requireAuth: false,
    },
    component: require('../views/auth/reset.vue')
  },
  {
    path:'/',
    name:'main',
    meta:{
      title: "首页",
      requireAuth: true,
    },
    component:require('../views/layout/layout.vue'),
    children: mainRouters
  },
  { path: '/404', component: require('../views/errorPage/404'), hidden: true },
  { path: '/401', component: require('../views/errorPage/401'), hidden: true },
  //{ path: '*', redirect: '/404', hidden: true }

]

export default routers;
