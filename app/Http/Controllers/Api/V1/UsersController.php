<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\UserRequest;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Auth;
use App\Traits\PassportToken;

class UsersController extends Controller
{
    //
    use PassportToken;

    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        Auth::login($user);

        $result = $this->getBearerTokenByUser($user, '1', false);
        return $this->response->array($result)->setStatusCode(201);

    }
    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
