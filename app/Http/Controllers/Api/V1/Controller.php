<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use App\Models\Urole;

class Controller extends BaseController
{
    //
    use Helpers;

    public function _hasDevice($device_id, $org_id = null){
      if($device_id && ($this->user()->has('devices', $device_id) || ($org_id && Organization::find($org_id)->has('devices', $device_id)))){
        return true;
      }
      else{
        return false;
      }
    }
    public function _index($roles, $where = null, $callback = null)
    {
      //$this->assertPermissions('index', $roles);
      if ($where === null) {
          $where = [DB::raw('1'), 1];
      }
      $items = call_user_func_array([static::$model, 'where'], $where);
      $with = Request::input('with');
      if ($with) {
          if (str_contains($with,',')) {
              $with = explode(',', $with);
          }
          $items = $items->with($with);
      }
      if ($callback) {
          $callback($items);
      }
      return $items->get();
    }
    public function _store($data, $roles) {

        $this->assertPermissions('store', $roles);
        return call_user_func([static::$model, 'create'], $data);
    }

    public function _update($id, $data, $roles) {
        $this->assertPermissions('update', $roles);
        $model = call_user_func([static::$model, 'find'], $id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    protected function _roles($type, $id){
      $items = call_user_func(array($this->user(),$type));

      $role_ids = array();
      if($items->where('id',$id)->first()){
        $role_ids = json_decode($items->where('id',$id)->first()->pivot->urole_ids);
      }
      else{
        $items = call_user_func(array($this->user(),$type));
        foreach($items->get() as $item){
          if($item->hasSub($id))
          {
            $role_ids = json_decode($item->pivot->urole_ids);
          }
        }
      }

      $roles = array();
      foreach($role_ids as $role_id){
        array_push($roles, Urole::find($role_id));
      }
      return $roles;
    }

    protected function _permissions($type, $id){
      $roles = $this->_roles($type, $id);
      $perms = collect([]);
      foreach($roles as $role){
        $perms = $perms->concat($role->permissions()->get());
      }

      return $perms->unique();
    }
    public function assertPermissions($action, $roles) {

        if (!empty(static::$permissions)) {
            $permissions = isset(static::$permissions[$action]) ? static::$permissions[$action]: @static::$permissions['all'];
            if (!$permissions) return;

            $this->user()->allows($permissions, $roles,false) || $this->response->errorUnauthorized("权限不足");
        }
    }
}
