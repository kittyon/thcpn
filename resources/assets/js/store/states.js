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
        }
        get Phone(){
          return localStorage.getItem("phone");
        }
    }
};

export default state;
