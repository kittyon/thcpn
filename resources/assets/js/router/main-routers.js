import deviceRouters from './device-routers'

export default[
  {
    path: '',
    name: 'main-dashboard',
    meta: { title:"设备信息",requireAuth: true },
    component: require('../views/main/dashboard.vue')
  },
  {
    path: 'devices/map',
    name: 'main-devices-map',
    meta:{ title:"设备地图",requireAuth: true},
    component: require('../views/main/device-map.vue')
  },
  {
    path: 'devices/list',
    name: 'main-devices-list',
    meta:{ title:"设备列表",requireAuth: true},
    component: require('../views/main/device-list.vue')
  },
  {
    path: 'devices',
    name: 'main-device',
    meta: { title:"设备信息",requireAuth: true },
    component: require('../views/main/device.vue'),
    children: deviceRouters
  }
]
