<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;
use App\Models\Organization;
use App\Transformers\DevicesTransformer;
use App\Transformers\DeviceDataTransformer;

class DevicesController extends Controller
{
    static $model = \App\Models\Device::class;
    static $permissions = [
      'index' =>['dev_r'],
      'show' =>['dev_r']
    ];
    //
    public function index(Request $request){
      $org_id = $request->input('org_id');
      if($org_id && $this->user()->has('organizations', $org_id)){
        return $this->response->collection(Organization::find($org_id)->devices()->get(), new DevicesTransformer());
      }
      else{
        return $this->response->collection($this->user()->devices()->get(),new DevicesTransformer());
        //return $this->response->array($this->user()->devices()->get()->toArray());
      }
    }

    public function show($device_id, Request $request){
      $org_id = $request->input('org_id');
      if($this->_hasDevice($device_id, $org_id)){
        return $this->response->item(Device::find($device_id), new DevicesTransformer());
      }
      else{
        return $this->response->errorUnauthorized('您无权获取该设备信息');
      }

    }
}
