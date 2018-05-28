import * as types from './types.js';
import config from '../config/index';

//actions常用于异步更改状态。也就是说它一般用来先发送请求，然后再commit
export default {
    UserLogin({commit}, data){
        commit(types.LOGIN, data);
    },
    UserLogout({commit}){
        commit(types.LOGOUT);
    },
    createToken({commit}, model){
      let params = {
          grant_type: 'password',
          client_id: config.api.client.id,
          client_secret: config.api.client.secret,
          username: model.username,
          password: model.password,
      };
      axios.post('authorizations', params).then(function(response){
        commit(types.LOGIN,response.data);
        console.log(response);
        return response.data.access_token;
      })
    }
}
