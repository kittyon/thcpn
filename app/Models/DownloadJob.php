<?php

namespace App\Models;


class DownloadJob extends Base
{
    //

    protected $table = 'download_jobs';

    protected $fillable = [
        'url', 'options', 'status'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getOptionsAttribute($v){
      return $v;
    }

    public function setOptionsAttribute($v){
      $this->attributes['options'] = $v;
    }
}
