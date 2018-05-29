<?php

namespace App\Transformers;

use App\Models\DeviceConfig;
use League\Fractal\TransformerAbstract;

class DeviceConfigTransformer extends TransformerAbstract
{
    public function transform(DeviceConfig $config)
    {
        return [
            'id' => $config->id,
            'data' => $config->data,
            'control' => $config->control,
        ];
    }
}
