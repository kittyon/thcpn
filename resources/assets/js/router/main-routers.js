export default{
  {
    path: '',
    name: 'main-dashboard',
    meta: { requiresAuth: true },
    component: require('../components/main/dashboard.vue')
  },
  {
    path: 'devices/map',
    name: 'main-devices-map',
    meta:{ requiresAuth: true},
    component: require('../components/main/device-map.vue')
  },
  {
    path: 'devices/list',
    name: 'main-devices-list',
    meta:{ requiresAuth: true},
    component: require('../components/main/device-list.vue')
  },
  {
    path: 'devices/:device_id',
    name: 'main-device',
    meta: { requireAuth: true },
    component: require('../components/main/device.vue')
  }
}
