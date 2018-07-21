<?php

namespace App\Transformers;

use App\Models\Organization;
use App\Models\Device;
use App\Models\DownloadJob;
use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Models\Urole;

class DownloadJobTransformer extends TransformerAbstract
{

    public function transform(DownloadJob $job)
    {
        $res = [
            'id' => $job->id,
            'url' => $job->url,
            'created_at' => $job->created_at,
            'options' => $job->option,
            'status' => $job->status

        ];


        return $res;
    }
}
