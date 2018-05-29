<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Transformers\DeviceDataTransformer;

class DeviceDataController extends Controller
{
    static $model = \App\Models\DeviceData::class;
    static $permissions = [
      'index' =>['dev_r']
    ];
    //
    public function index(Request $request){

      $device_id = $request->input('device_id');
      $org_id = $request->input('org_id');

      if($this->_hasDevice($device_id, $org_id)){
        $datas = $this->_index(['device_id','=',$device_id],function (&$items) use ($request) {
            $items->orderBy('ts', 'asc');
            if ($request->input('start_at')) {
                $items->where('ts', '>=', $request->input('start_at'));
            }
            if ($request->input('end_at')) {
                $items->where('ts', '<=', $request->input('end_at'));
            });
        return $this->response->collection($datas, new DeviceDataTransformer());
      }
      else{
        return $this->response->errorUnauthorized('您无权获取相关数据信息');
      }
    }
}
