<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Notifications\InfoNotification;
use App\Events\NotificationRead;
use App\Events\NotificationReadAll;
use App\Transformers\NotificationTransformer;

class NotificationController extends Controller
{
    //

    public function index(Request $request){
      $query = $this->user()->unreadNotifications();
      $limit = $request->input('limit', null);
      if ($limit) {
            $query = $query->limit($limit);
      }
      $notifications = $query->get()->each(function ($n) {
            $n->created = $n->created_at->toIso8601String();
      });


      return $this->response->array($notifications);
    }
    public function all(Request $request){
      $per_page = $request->input('limit',null);
      if(!is_null($per_page)){
        return $this->response->paginator($this->user()->Notifications()->paginate($per_page), new NotificationTransformer());
      }
      else{
        return $this->response->collection($this->user()->Notifications()->get(), new NotificationTransformer());
      }
    }
    public function store(Request $request)
    {
        $this->user()->notify(new InfoNotification);

        return $this->response->noContent();
    }

    /**
     * Mark user's notification as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $this->user()
                                ->unreadNotifications()
                                ->where('id', $id)
                                ->first();

        if (is_null($notification)) {
            return response()->json('Notification not found.', 404);
        }

        $notification->markAsRead();

        event(new NotificationRead($this->user()->id, $id));
    }

    /**
     * Mark all user's notifications as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function markAllRead(Request $request)
    {
        $this->user()
                ->unreadNotifications()
                ->get()->each(function ($n) {
                    $n->markAsRead();
                });

        event(new NotificationReadAll($this->user()->id));
    }
}
