<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Transformers\DeviceDataTransformer;
use Illuminate\Support\Facades\Redis;
use App\Models\DeviceData;

class DeviceDataController extends Controller
{
    static $model = \App\Models\DeviceData::class;
    static $permissions = [
      'index' =>['dev_r']
    ];
    //

    public function indexth($device_id, Request $request){
      $datas = $this->_index([], ['device_id','=',$device_id],function(&$items)use($request){
          $items->orderBy('ts', 'asc');
          if ($request->input('start_at')) {
              $items->where('ts', '>=', $request->input('start_at'));
          }
          if ($request->input('end_at')) {
              $items->where('ts', '<=', $request->input('end_at'));
          }});
      return $this->response->collection($datas, new DeviceDataTransformer());
    }
    public function index($device_id, Request $request){

      //$device_id = $request->input('device_id');
      $org_id = $request->input('org_id');
      if(!is_null($org_id)){
        $roles = $this->_roles('organizations',$org_id);
      }
      else{
        $roles = $this->_roles('devices',$device_id);
      }
      if($this->_hasDevice($device_id, $org_id)){
        $datas = $this->_index($roles, ['device_id','=',$device_id],function(&$items)use($request){
            $items->orderBy('ts', 'asc');
            if ($request->input('start_at')) {
                $items->where('ts', '>=', $request->input('start_at'));
            }
            if ($request->input('end_at')) {
                $items->where('ts', '<=', $request->input('end_at'));
            }});
        return $this->response->collection($datas, new DeviceDataTransformer());
      }
      else{
        return $this->response->errorUnauthorized('您无权获取相关数据信息!');
      }
    }
    public function first($device_id,$data_type, Request $request){

      $org_id = $request->input('org_id', null);

      if(!$this->_hasDevice($device_id, $org_id)){
        return $this->response->errorUnauthorized('您无权获取相关数据信息');
      }

      $data = json_decode(Redis::get($device_id.'-'.$data_type));

      if(!$data){
        if(!is_null($org_id)){
          $roles = $this->_roles('organizations',$org_id);
        }
        else{
          $roles = $this->_roles('devices',$device_id);
        }
        $data = $this->_index($roles, ['device_id','=',$device_id],function (&$items) use ($data_type) {
            $items->where('type','=',$data_type)->orderBy('ts', 'asc');
          })->first();
        Redis::set($device_id.'-'.$data_type, json_encode($data));
        return $this->response->item($data, new DeviceDataTransformer());
      }
      else{
        $tmp = new DeviceData();
        $tmp->data = $data->data;
        $tmp->ts = $data->ts;
        $tmp->id = $data->id;
        $data = $tmp;
        return $this->response->item($data, new DeviceDataTransformer());
      }


    }

}
