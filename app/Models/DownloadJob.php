<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadJob extends Model
{
    //
    use SoftDeletes;

    protected $table = 'download_jobs';

    protected $fillable = [
        'url', 'options', 'status'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
