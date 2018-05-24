<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class URole extends Model
{
    //
    protected $fillable = ['name', 'display_name'];

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }
}
