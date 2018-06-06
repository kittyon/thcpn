let menu = {};

/**
 * 首页
 * @type {{name: string, path: string, icon: string}}
 */
menu.home = {
  name: '首页',
  path: '/',
  icon: 'fa fa-tachometer',
};


/**
 * 字体图标
 * @type {{name: string, icon: string, children: {}}}
 */
menu.device = {
  name: '设备管理',
  icon: 'fa fa-th',
  children: {}
};
let devs = menu.device.children;

devs.map = {
  name: '设备地图',
  path: '/devices/map',

};
devs.list = {
  name: '设备列表',
  path: '/devices/list',
};


export default menu;
