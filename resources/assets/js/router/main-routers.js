import deviceRouters from './device-routers';

export default[
  {
    path: 'profile',
    name: 'main-profile',
    meta:{ title: "个人中心", requireAuth: true},
    component: require('../views/main/profile.vue')
  },
  {
    path: 'notification',
    name: 'main-notification',
    meta:{title:"通知中心", requireAuth: true},
    component: require('../views/main/notification.vue')
  },
  {
    path: 'download',
    name: 'main-download',
    meta:{title:"数据下载", requireAuth: true},
    component: require('../views/main/download.vue')
  },
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
    path: 'orgs/list',
    name: 'main-orgs-list',
    meta:{ title:"组织列表", requireAuth: true},
    component: require('../views/main/org-list.vue')
  },
  {
    path: 'device/:id',
    name: 'main-device',
    meta: { title:"设备信息",requireAuth: true },
    component: require('../views/main/device.vue'),
    children: deviceRouters
  },
  {
    path: 'org/:id',
    name: 'main-org',
    meta: {title:"组织信息", requireAuth: true},
    component: require('../views/main/org.vue'),
  }
]
