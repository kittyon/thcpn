<?php

namespace App\Transformers;

use App\Models\Device;
use League\Fractal\TransformerAbstract;

class DevicesTransformer extends TransformerAbstract
{
    public function transform(Device $device)
    {
        return [
          'id' => $device->id,
          'name' => $device->name,
          'iccid' => $device->iccid,
          'version' => $device->version,
          'status' =>$device->status,
        ];
    }
}
