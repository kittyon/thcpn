<?php

namespace App\models;


class DeviceUser extends Base
{
    //
    const TABLE = 'device_user';
    protected $table = self::TABLE;

    protected $fillable = ['device_id', 'user_id','urole_ids'];

    public function user(){
      return $this->belongsTo('App\Models\User');
      //return $this->hasMany('App\Models\Device');
    }

    public function device(){
      return $this->belongsTo('App\Models\Device');
    }

    public function setUroleIdsAttribute($v) {
        $this->attributes['urole_ids'] = $v;
    }

    public function getUroleIdsAttribute($v){
      return $v;
    }
}
