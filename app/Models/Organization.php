<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    use SoftDeletes;

    protected $table = 'organizations';

    protected $fillable = ['name', 'description', 'organization_id'];

    public function Devices(){
      return $this->belongsToMany('App\Models\Device');
    }

}
