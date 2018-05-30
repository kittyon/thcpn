import registerRouters from './register-routers'

const routers = [
  {
    path:'/login',
    name: 'login',
    meta:{
      requireAuth: false,
    },
    component:require('../components/auth/login.vue')
  },
  {
    path:'/register',
    name:'register',
    meta:{
      requireAuth:false,
    },
    component:require('../components/auth/register.vue'),
    children:registerRouters
  }
  {
    path:'/',
    name:'main',
    meta:{
      requireAuth: true,
    },
    component:require('../components/layout.vue')
  }

]

export default routers;
