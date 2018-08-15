<?php

namespace App\Transformers;
use Illuminate\Support\Facades\Log;

use League\Fractal\TransformerAbstract;
use Illuminate\Notifications\DatabaseNotification;

class NotificationTransformer extends TransformerAbstract
{

    public function transform(DatabaseNotification $notify)
    {
        Log::info($notify);
        $res=["id"=> $notify->id,
              "action_url"=> $notify['data']['action_url'],
              "created" => $notify->created_at->toIso8601String(),
              "read_at"=>$notify->read_at->toIso8601String(),
              "title"=> $notify['data']['title'],
              "body"=> $notify['data']['body']
            ];

        return $res;
    }
}
