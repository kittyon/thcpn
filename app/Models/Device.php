<?php

namespace App\Models;


class Device extends Base
{
    //
    const TABLE = 'devices';
    protected $table = self::TABLE;

    protected $fillable = ['name', 'version', 'iccid'];

    public function configs() {
        return $this->hasMany('App\Models\DeviceConfig');
    }

    public function config() {
        return $this->configs()->orderBy('updated_at', 'desc')->limit(1);
    }

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitationable');
    }

    public function getFullNameAttribute($v){
      return $this->attributes['id']."-".$this->attributes['name'];
    }
}
