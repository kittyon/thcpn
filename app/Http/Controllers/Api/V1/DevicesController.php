<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;
use App\Models\Organization;
use App\Models\Urole;
use App\Transformers\DevicesTransformer;
use App\Transformers\DeviceDataTransformer;
use App\Http\Requests\Api\DeviceRequest;
use App\Http\Requests\Api\DeviceAttachRequest;

class DevicesController extends Controller
{
    static $model = \App\Models\Device::class;
    static $permissions = [
      'index' =>['dev_r','org_r'],
      'show' =>['dev_r', 'org_r'],
      'detach' =>['dev_w','org_w'],
    ];
    //
    public function index(Request $request){
      $org_id = $request->input('org_id');
      $per_page = $request->input('limit',null);

      if($org_id){
        $roles = $this->_roles('organizations',$org_id);
        $this->assertPermissions('index', $roles);
        if(!is_null($per_page)){
          return $this->response->paginator(Organization::find($org_id)->devices()->paginate($per_page),
          new DevicesTransformer($this->_permissions('organizations', $org_id)));
        }
        else{
          return $this->response->collection(
            Organization::find($org_id)->devices()->get(),
           new DevicesTransformer($this->_permissions('organizations', $org_id)));
        }
      }
      else{
        if(!is_null($per_page)){
          return $this->response->paginator($this->user()->devices()->paginate($per_page),
          new DevicesTransformer());
        }
        else{
          return $this->response->collection($this->user()->devices()->get(),
          new DevicesTransformer());
        }
        //return $this->response->array($this->user()->devices()->get()->toArray());
      }
    }

    public function show($device_id, Request $request){
      $org_id = $request->input('org_id');
      if($this->_hasDevice($device_id, $org_id)){
        $perms = array();
        if(is_null($org_id)){
          $perms = $this->_permissions('devices',$device_id);
        }
        else{
          $perms = $this->_permissions('organizations', $org_id);
        }
        return $this->response->item(Device::find($device_id), new DevicesTransformer($perms));
      }
      else{
        return $this->response->errorUnauthorized('您无权获取该设备信息');
      }

    }

    public function update($device_id, DeviceRequest $request){
      $org_id = $request->input('org_id', null);
      $model = null; $perms = null;
      if($this->_hasDevice($device_id, $org_id)){
        if(is_null($org_id)){
          $model = $this->_update($device_id, $request->all(), $this->_roles('devices', $device_id));
          $perms = $this->_permissions('devices',$device_id);
        }
        else{
          $model = $this->_update($device_id, $request->all(), $this->_roles('organizations', $org_id));
          $perms = $this->_permissions('organizations', $org_id);
        }
        return $this->response->item($model, new DevicesTransformer($perms));
      }
      else{
        return $this->response->errorUnauthorized('您无权更新该设备信息');
      }

    }
    public function attach(DeviceAttachRequest $request){
      $dev = Device::where('iccid','=',$request->iccid)->first();
      if(!$dev){
        return $this->response->error('iccid 设备不存在',410);
      }
      if($dev->active == 1){
        return $this->response->error('该设备已激活',410);
      }

      if($request->attach_type == 'user'){
        $role = Urole::where('name' ,'=','deviceManager')->first();
        User::find($request->user_id)->devices()->attach($dev->id, ['urole_ids'=>json_encode([$role->id])]);
        $perms = $role->permissions()->get();
        $dev->active = 1;
        $dev->save();
        return $this->response->item($dev, new DevicesTransformer($perms));
      }

      if($request->attach_type == 'org'){
        $role = Urole::where('name','=', 'manager')->first();
        Organization::find($request->org_id)->devices()->attach($dev->id,['urole_ids'=>json_encode([$role->id])]);
        $perms = $role->permissions()->get();
        $dev->active = 1;
        $dev->save();
        return $this->response->item($dev, new DevicesTransformer($perms));

      }
    }

    public function detach($device_id, Request $request){
      $org_id = $request->input('org_id', null);

      if(is_null($org_id)){
        $this->assertPermissions('detach', $this->_roles('devices', $device_id));
        $this->user()->devices()->detach($device_id);
      }
      else{
        $this->assertPermissions('detach', $this->roles('organizations', $org_id));
        Organization::find($org_id)->devices()->detach($device_id);
      }
    }
}
