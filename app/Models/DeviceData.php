<?php

namespace App\Models;


class DeviceData extends Base
{
    //
    protected $table = 'device_data';

    public function getDataAttribute($value) {
        return json_decode($value, true);
    }

    public function config() {
        return $this->belongsTo('App\Models\DeviceConfig', 'device_config_id');
    }

    public function setDataAttribute($v) {
        $this->attributes['data'] = json_encode($v);
    }

    public function setDataTextAttribute($v){
        $this->attributes['data'] = $v;
    }

    public function getDataTextAttribute($v){
      return $v;
    }

    public function device(){
      $this->BelongsTo("App\Models\Device");
    }
}
