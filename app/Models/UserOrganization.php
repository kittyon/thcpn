<?php

namespace App\models;



class UserOrganization extends Base
{
    //
    const TABLE = 'organization_user';
    protected $table = self::TABLE;

    protected $fillable = ['organization_id', 'user_id','urole_ids'];

    public function user(){
      return $this->belongsTo('App\Models\User');
      //return $this->hasMany('App\Models\Device');
    }

    public function organization(){
      return $this->belongsTo('App\Models\Organization');
    }

    public function setUroleIdsAttribute($v) {
        $this->attributes['urole_ids'] = $v;
    }

    public function getUroleIdsAttribute($v){
      return $v;
    }
}
