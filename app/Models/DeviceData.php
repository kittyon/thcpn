<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
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
}
