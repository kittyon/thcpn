<?php

namespace App\Transformers;

use App\Models\Organization;
use League\Fractal\TransformerAbstract;

class OrganizationTransformer extends TransformerAbstract
{
    public function transform(Organization $org)
    {
        return [
            'id' => $org->id,
            'name' => $org->name,
            'description' => $org->description,
          
        ];
    }
}
