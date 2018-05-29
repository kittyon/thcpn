<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
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
    public function _index($where = null, $callback = null)
    {
      $this->assertPermissions('index');
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
      return $items;
    }
    public function _store($data) {
        $this->assertPermissions('store');
        return call_user_func([static::$model, 'create'], $data);
    }

    public function assertPermissions($action) {
        if (!empty(static::$permissions)) {
            $permissions = isset(static::$permissions[$action]) ? static::$permissions[$action]: @static::$permissions['all'];
            if (!$permissions) return;
            $this->user()->allows($permissions, false) || $this->response->errorUnauthorized("权限不足");
        }
    }
}
