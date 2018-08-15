const state = {
    currentUser:{
        get ExpiresIn(){
            return localStorage.getItem("expires_in");
        },
        get AccessToken(){
            return localStorage.getItem("access_token");
        },
        get RefreshToken(){
            return localStorage.getItem("refresh_token");
        },
        get Device(){
          return JSON.parse(localStorage.getItem("device")||"{}");
        },
        get Org(){
          return localStorage.getItem("org");
        }
    },
    user: {
      get Current(){
        return JSON.parse(localStorage.getItem("user")||"{}");
      },
      set Current(c){
        localStorage.setItem('user', c);
      }
    },
    /**
     * 顶部工具栏
     * @type {Object}
     */
    header: {
      /**
       * 站点名称
       * @type {String}
       */
      name: 'THCreate',

      /**
       * 顶部菜单
       * @type {Array}
       */
      menus: [

        {
          text: '设备',
          icon: 'plus',
          name: 'device',
          children: [
            { text: '地图显示', name: 'main-devices-map' },
            { text: '列表显示', name: 'main-devices-list' },
          ]
        }
      ]
    },
};

export default state;
