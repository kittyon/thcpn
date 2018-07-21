<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Urole;
use App\Models\Organization;
use App\Transformers\OrganizationTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\Api\UserDetachRequest;
use App\Http\Requests\Api\OrganizationRequest;

class OrganizationsController extends Controller
{
  static $model = \App\Models\Organization::class;
  static $permissions = [
    'children' =>['org_r'],
    'store' => ['org_w'],
    'update' => ['org_w'],
    'users' =>['org_w']
  ];
    //
    public function index(Request $request){
      return $this->response->collection($this->user()->organizations()->get(), new OrganizationTransformer());
    }

    public function show($org_id, Request $request){

      if($this->user()->has('organizations',$org_id, true)){
        $perms = $this->_permissions('organizations',$org_id);
        return $this->response->item(Organization::find($org_id), new OrganizationTransformer($perms));
      }
      else{
        return $this->response->errorUnauthorized('您无权获该组织信息');
      }
    }
    public function children($org_id, Request $request){

      if($this->user()->has('organizations',$org_id, true)){
        $roles = $this->_roles('organizations', $org_id);
        $perms = $this->_permissions('organizations',$org_id);
        $this->assertPermissions('children', $roles);
        return $this->response->collection(Organization::find($org_id)->children()->get(), new OrganizationTransformer($perms));
      }
      else{
        return $this->response->errorUnauthorized('您无权获取附属组织信息');
      }
    }

    public function update($org_id, OrganizationRequest $request){
      if($this->user()->has('organizations', $org_id)){
        $model = $this->_update($org_id, $request->all(), $this->_roles('organizations', $org_id));
        $perms = $this->_permissions('organizations',$org_id);
        return $this->response->item($model, new OrganizationTransformer($perms));
      }
      else{
        return $this->response->errorUnauthorized('您无权更新该组织信息');
      }
    }

    public function store(OrganizationRequest $request){
      $newData = [
          'name' => $request->name,
          'description' => $request->description,
        ];
      if($request->organization_id){
        if(!Organization::find($request->organization_id)->get()){
          return $this->response->errorUnauthorized('您提交组织id错误');
        }
        else{
          $roles = $this->_roles('organizations',$request->organization_id);
          $newData['organization_id'] = $request->organization_id;
          $org = $this->_store($newData, $roles);
          return $this->response->item($org, new OrganizationTransformer());
        }
      }

      $org = Organization::create($newData);
      $role = Urole::where('name','manager')->first();
      $this->user()->organizations()->attach($org->id,['urole_ids'=>json_encode([$role->id])]);
      $perms = $this->_permissions('organizations',$org->id);
      return $this->response->item($org, new OrganizationTransformer($perms));

    }

    public function detach($org_id, UserDetachRequest $request){

      $roles = $this->_roles('organizations',$org_id);
      $this->assertPermissions('organizations', $roles);

      $org = Organization::find($org_id);

      $org->users()->detach($request->user_id);
      return $this->response->noContent();
    }

    public function users($org_id, Request $request){
      $per_page = $request->input('limit',null);
      $roles = $this->_roles('organizations',$org_id);
      $this->assertPermissions('users', $roles);
      $org = Organization::find($org_id);
      if($org){
        if(!is_null($per_page)){
          return $this->response->paginator($org->users()->paginate($per_page),
          new UserTransformer());
        }
        else{
          return $this->response->collection($org->users()->get(),
          new UserTransformer());
        }

      }
    }
}
