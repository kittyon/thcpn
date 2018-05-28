<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    //
    use SoftDeletes;

    protected $table = 'devices';

    protected $fillable = ['name', 'version', 'iccid'];

    public function configs() {
        return $this->hasMany('App\Models\DeviceConfig');
    }

    public function config() {
        return $this->configs()->orderBy('updated_at', 'desc')->limit(1);
    }
}
