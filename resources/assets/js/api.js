export default{
  get_current_org(){
    var current_org = parseInt(Cookie.get('current_org'));
    if(isNaN(current_org)){
      current_org = 0;
    }
    return current_org;
  },
}
