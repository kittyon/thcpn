<?php

namespace App\Models;


class DeviceConfig extends Base
{
    //
    protected $fillable = ['data', 'control', 'device_id','version'];

    protected $table = 'device_config';

    public function setDataAttribute($v) {
        $this->attributes['data'] = json_encode($v);
    }

    public function setControlAttribute($v) {
        $this->attributes['control'] = json_encode($v);
    }
    public function getDataAttribute($v) {
        return json_decode($v, true);
    }

    public function getControlAttribute($v) {
        return json_decode($v, true);
    }

    public function device(){
      return $this->belongsTo('App\Models\Device');
    }
}
