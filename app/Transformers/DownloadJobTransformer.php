<?php

namespace App\Transformers;

use App\Models\Organization;
use App\Models\Device;
use App\Models\DownloadJob;
use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Models\Urole;
use Illuminate\Support\Facades\Log;

class DownloadJobTransformer extends TransformerAbstract
{

    public function transform(DownloadJob $job)
    {
        $devices = array();
        $options = json_decode($job->options, true);
        Log::info($options['devices']);
        foreach ($options['devices'] as $dev_id) {
          // code...
          array_push($devices,Device::find($dev_id));
        }
        $options['devices'] = $devices;
        $res = [
            'id' => $job->id,
            'url' => $job->url,
            'created_at' => $job->created_at,
            'options' => $options,
            'status' => $job->status

        ];


        return $res;
    }
}
