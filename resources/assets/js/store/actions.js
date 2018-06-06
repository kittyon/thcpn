import * as types from './types.js';
import config from '../config/index';

//actions常用于异步更改状态。也就是说它一般用来先发送请求，然后再commit
export default {
    userLogin({commit}, data){
        commit(types.LOGIN, data);
    },
    userLogout({commit}){
        commit(types.LOGOUT);
    },
    getCurrentUser({commit}){
      axios.get('user').then(res=>{
        commit(types.USER, JSON.stringify(res.data));
        
      });
    },
    deleteToken({commit}){
      commit(types.DELTOKEN);
    },
    createToken({commit}, model){
      let params = {
          grant_type: 'password',
          client_id: config.api.client.id,
          client_secret: config.api.client.secret,
          username: model.username,
          password: model.password,
      };
      return axios.post('authorizations', params).then(res => {
        commit("login",res.data);
        console.log(res)
        return res.data;
      });
    }
}
