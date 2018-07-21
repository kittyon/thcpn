<?php

namespace App\Transformers;

use App\Models\Organization;
use App\Models\Device;
use App\Models\Invitation;
use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Models\Urole;

class InvitationTransformer extends TransformerAbstract
{

    public function transform(Invitation $inv)
    {
        $res = [
            'id' => $inv->id,
            'owner' => User::find($inv->owner_id),
            'user' => User::find($inv->user_id),
            'role' => Urole::find($inv->role_id),
            'status' => $inv->status

        ];
        if($inv->invitationable_type == "organizations"){
          $res['organization'] = Organization::find($inv->invitationable_id);
        }

        if($inv->invitationable_type == "devices"){
          $res['device'] = Device::find($inv->invitationable_id);
        }


        return $res;
    }
}
