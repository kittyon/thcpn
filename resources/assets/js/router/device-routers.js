export default[
  {
    path: '',
    name: 'device-dashboard',
    meta: { title:"设备信息",requireAuth: true },
    component: require('../views/main/device/index.vue')
  },
  {
    path: 'config',
    name: 'device-config',
    meta:{ title:"设备配置",requireAuth: true},
    component: require('../views/main/device/config.vue')
  },
  {
    path: 'datas',
    name: 'device-datas',
    meta:{ title:"设备数据",requireAuth: true},
    component: require('../views/main/device/datas.vue')
  },
  {
    path: 'gallery',
    name: 'device-gallery',
    meta: { title:"设备图像",requireAuth: true },
    component: require('../views/main/device/gallery.vue')
  }
]
