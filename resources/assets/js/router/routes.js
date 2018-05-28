const routers = [
  {
    path:'/login',
    name: 'login',
    meta:{
      requireAuth: false,
    },
    component:require('../components/login.vue')
  },
  {
    path:'/',
    name:'exp',
    meta:{
      requireAuth: true,
    },
    component:require('../components/ExampleComponent.vue')
  }

]

export default routers;
