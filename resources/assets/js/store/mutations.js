import * as types from './types.js'

const mutations = {
    [types.LOGIN]: (state, data) => {
        localStorage.setItem('expires_in',data.expires_in);
        localStorage.setItem('access_token',data.access_token);
        localStorage.setItem('refresh_token',data.refresh_token);
    },
    [types.LOGOUT]: (state) => {
        localStorage.removeItem('expires_in');
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
        localStorage.removeItem('user');
    },
    [types.DELTOKEN]: (state)=>{
      localStorage.removeItem('expires_in');
      localStorage.removeItem('access_token');
    },
    [types.USER]: (state, data)=>{
      localStorage.setItem('user', data);
    },
    [types.DEVICE]:(state, data)=>{
      localStorage.setItem('device', JSON.stringify(data));
    },
    [types.ORG]:(state, data)=>{
      localStorage.setItem('org', data);
    }
};

export default mutations;
