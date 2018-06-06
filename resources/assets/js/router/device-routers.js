export default[
  {
    path: '/:id',
    name: 'device-dashboard',
    meta: { title:"设备信息",requireAuth: true },
    component: require('../views/main/device/index.vue')
  },
  {
    path: '/:id/config',
    name: 'device-config',
    meta:{ title:"设备配置",requireAuth: true},
    component: require('../views/main/device/config.vue')
  },
  {
    path: '/:id/datas',
    name: 'device-datas',
    meta:{ title:"设备数据",requireAuth: true},
    component: require('../views/main/device/datas.vue')
  },
  {
    path: '/:id/gallery',
    name: 'main-device',
    meta: { title:"设备信息",requireAuth: true },
    component: require('../views/main/device.vue')
  }
]
