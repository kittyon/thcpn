<?php

namespace App\Transformers;

use App\Models\DeviceData;
use League\Fractal\TransformerAbstract;

class DeviceDataTransformer extends TransformerAbstract
{
    public function transform(DeviceData $data)
    {
        return [
            'id' => $data->id,
            'data' => $data->data,
            'ts' => $data->ts
        ];
    }
}
