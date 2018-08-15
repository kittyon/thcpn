<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Http\Requests\Api\InvitationRequest;
use App\Transformers\InvitationTransformer;

class InvitationController extends Controller
{
    //
    static $model = \App\Models\Invitation::class;
    static $permissions = [
      'store'=>['org_w', 'dev_w']
    ];

    public function store(InvitationRequest $request){
      $roles = $this->_roles($request->invitationable_type, $request->invitationable_id);

      $request['owner_id'] = $this->user()->id;

      //notify
      $user = User::find($request->user_id);
      $title = $request->invitationable_type." invitation";
      $item = call_user_func(array($this->user(),$request->invitationable_type))->where('id','=',$request->invitationable_id)->first();
      $body = "invitation from ".$this->user()->name." about ".$item->name;
      $action_url = env('APP_URL')."/#/profile";
      $user->notify(new InfoNotification($title, $body, $action_url));

      return $this->response->item($this->_store($request, $roles), new InvitationTransformer());
    }
    public function indexOfOwner(Request $request){
      $id = $this->user()->id;
      $per_page = $request->input('limit',null);
      $invitationable_type = $request->input('invitationable_type', "");
      if(!is_null($per_page)){
        $invs = \App\Models\Invitation::where('owner_id',$id)->where('invitationable_type',$invitationable_type)->paginate($per_page);
        return $this->response->paginator($invs, new InvitationTransformer());
      }
      else{
        $invs = \App\Models\Invitation::where('owner_id',$id)->where('invitationable_type',$invitationable_type)->get();
        return $this->response->collection($invs, new InvitationTransformer());
      }

    }
    public function indexOfUser(Request $request){
      $id = $this->user()->id;
      $per_page = $request->input('limit',null);
      $invitationable_type = $request->input('invitationable_type', "");
      if(!is_null($per_page)){
        $invs = \App\Models\Invitation::where('user_id',$id)->where('invitationable_type',$invitationable_type)->paginate($per_page);
        return $this->response->paginator($invs, new InvitationTransformer());
      }
      else{
        $invs = \App\Models\Invitation::where('user_id',$id)->where('invitationable_type',$invitationable_type)->get();
        return $this->response->collection($invs, new InvitationTransformer());
      }

    }
    public function pass($id){
      $Inv = \App\Models\Invitation::find($id);
      if($this->user()->id == $Inv->user_id){
        $Inv->status = 'pass';
        $Inv->save();
        call_user_func($this->user(), $Inv->invitationable_type)->attach($Inv->invitationable_id,['urole_ids'=>[$Inv->role_id]]);



        return $this->response->item($Inv, new InvitationTransformer());
      }
    }

    public function unpass($id){
      $Inv = \App\Models\Invitation::find($id);
      if($this->user()->id == $Inv->user_id){
        $Inv->status = 'unpass';
        $Inv->save();
        return $this->response->item($Inv, new InvitationTransformer());
      }
    }

    public function delete($id){
      $Inv = \App\Models\Invitation::find($id);
      if($this->user()->id == $Inv->owner_id){
        $Inv->delete();
        return $this->response->noContent();
      }
    }

}
