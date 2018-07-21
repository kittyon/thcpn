<?php

namespace App\Models;


class Invitation extends Base
{
    protected $table = 'invitations';
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','owner_id','role_id','status'
    ];

    public function invitationable(){
      $this->morphTo();
    }
    public function user(){
      $this->hasOne('App\Models\User', 'user_id');
    }
    public function owner(){
      $this->hasOne('App\Models\User', 'owner_id');
    }




}
