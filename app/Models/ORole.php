<?php

namespace App\Models;

class Orole extends Base
{
    //
    protected $fillable = ['name', 'display_name'];
    protected $table = 'oroles';

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }

}
