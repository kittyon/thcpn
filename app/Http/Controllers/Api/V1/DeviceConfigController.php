<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\DeviceConfig;
use App\Models\Device;
use App\Transformers\DeviceConfigTransformer;
use App\Http\Requests\Api\DataConfigRequest;

class DeviceConfigController extends Controller
{
    static $model = \App\Models\DeviceConfig::class;
    static $permissions = [
      'store' =>['dev_w'],
      'last' =>['dev_r'],
      'show' =>['dev_r']
    ];
    //
    public function last($device_id, Request $request){
      $org_id = $request->input('org_id');

      if($this->_hasDevice($device_id, $org_id)){
        return $this->response->collection(Device::find($device_id)->config()->get(), new DeviceConfigTransformer());
      }
      else{
        return $this->response->errorUnauthorized('您无权获取最新信息');
      }
    }
    public function show($device_id, $config_id, Request $request){
      $org_id = $request->input('org_id');
      if($this->_hasDevice($device_id, $org_id)&& Device::find($device_id)->has('configs',$config_id)){
        return $this->response->item(DeviceConfig::find($config_id), new DeviceConfigTransformer());
      }
      else{
        return $this->response->errorUnauthorized('您无权获取该配置信息');
      }
    }
    public function store($device_id, DataConfigRequest $request){
      if($this->_hasDevice($device_id)){
        $body = $request->all();
        $body['device_id'] = $device_id;
        return $this->response->item($this->_store($body), new DeviceConfigTransformer());
      }
      else{
        return $this->response->errorUnauthorized('您无权创建该配置信息');
      }

    }
}
