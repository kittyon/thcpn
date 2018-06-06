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
    meta:{
      title: "注册",
      requireAuth:false,
    },
    component:require('../views/auth/register.vue'),
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
  }

]

export default routers;
