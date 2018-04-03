<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\Image;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Auth;


class UsersController extends Controller
{
    //
    public function index(){
        return $this->response->array(User::all());
    }

    //当前登录用户信息
    public function me(){

       return $this->response->item(Auth::guard('api')->user(),new UserTransformer());
    }

    //注册
    public function store(UserRequest $userRequest){

        $capthcha=\Cache::get($userRequest->captcha_key);

        if(!$capthcha){
            return $this->response->error('图片验证码已经失效','422');
        }

        if(!hash_equals($capthcha['code'],$userRequest->captcha_code)){
            //验证码错误，清缓存
            \Cache::forget($userRequest->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        };

        $user=User::create([
            'name'=>$userRequest->name,
            'email'=>$userRequest->email,
            'password'=>bcrypt($userRequest->password),
        ]);

        \Cache::forget($userRequest->captcha_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }


    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

}
