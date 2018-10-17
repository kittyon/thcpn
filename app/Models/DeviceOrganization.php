<?php

namespace App\Models;

class DeviceOrganization extends Base
{
    //
    const TABLE = 'device_organization';
    protected $table = self::TABLE;

    protected $fillable = ['organization_id', 'device_id'];

    public function device(){
      return $this->belongsTo('App\Models\Device');
      //return $this->hasMany('App\Models\Device');
    }

    public function organization(){
      return $this->belongsTo('App\Models\Organization');
    }

}
