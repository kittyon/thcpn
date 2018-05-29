<?php

namespace App\Models;


class Device extends Base
{
    //

    protected $table = 'devices';

    protected $fillable = ['name', 'version', 'iccid'];

    public function configs() {
        return $this->hasMany('App\Models\DeviceConfig');
    }

    public function config() {
        return $this->configs()->orderBy('updated_at', 'desc')->limit(1);
    }
}
