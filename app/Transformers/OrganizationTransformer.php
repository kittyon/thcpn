<?php

namespace App\Transformers;

use App\Models\Organization;
use League\Fractal\TransformerAbstract;
use App\Models\Urole;

class OrganizationTransformer extends TransformerAbstract
{
    protected  $perms = null;

    public function __construct($perms = null){

      $this->perms = $perms;

    }
    public function transform(Organization $org)
    {
        $res = [
            'id' => $org->id,
            'name' => $org->name,
            'description' => $org->description,
            'children_count'=>$org->children()->get()->count()

        ];
        if(is_null($this->perms) && !is_null($org->pivot)){
          $roles = array();
          $role_ids = json_decode($org->pivot->urole_ids);
          foreach($role_ids as $role_id){
            array_push($roles, Urole::find($role_id));
          }
          $perms = collect([]);
          foreach($roles as $role){
            $perms = $perms->concat($role->permissions()->get());
          }
          $perms = $perms->unique();
          $res['perms'] = $perms;
        }
        else{
          $res['perms'] = $this->perms;
        }


        return $res;
    }
}
