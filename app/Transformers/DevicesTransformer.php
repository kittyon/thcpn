<?php

namespace App\Transformers;

use App\Models\Device;
use League\Fractal\TransformerAbstract;
use App\Models\Urole;
use Illuminate\Support\Facades\Log;
class DevicesTransformer extends TransformerAbstract
{
    protected  $perms = null;

    public function __construct($perms = null){

      $this->perms = $perms;

    }
    public function transform(Device $device)
    {
        $res = [
          'id' => $device->id,
          'name' => $device->name,
          'iccid' => $device->iccid,
          'version' => $device->version,
          'status' =>$device->status,
          'lat' => $device->lat,
          'lon' => $device->lon,
        ];
        if(is_null($this->perms) && !is_null($device->pivot)){
          $roles = array();
          $role_ids = json_decode($device->pivot->urole_ids);
          foreach($role_ids as $role_id){
            array_push($roles, Urole::find($role_id));
          }
          $perms = collect([]);
          foreach($roles as $role){
            $perms = $perms->concat($role->permissions()->get());
          }
          $perms = $perms->unique();
          $res['perms'] = $perms;
        }
        else{
          $res['perms'] = $this->perms;
        }

        return $res;
    }
}
