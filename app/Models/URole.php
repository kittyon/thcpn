<?php

namespace App\Models;

class Urole extends Base
{
    //
    protected $fillable = ['name', 'display_name'];

    protected $table = 'uroles';

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }
}
