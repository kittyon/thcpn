<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use use App\Transformers\OrganizationTransformer;

class OrganizationController extends Controller
{
    //
    public function index(Request $request){
      return $this->response->collection($this->user()->organizations()->get(), new OrganizationTransformer());
    }
}
