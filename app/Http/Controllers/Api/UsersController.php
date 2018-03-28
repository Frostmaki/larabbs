<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;


class UsersController extends Controller
{
    //
    public function index(){
        return User::all();
    }
    public function me(){

       return $this->response->item($this->user(),new UserTransformer());
    }

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

        return $this->response->created();
    }

}
