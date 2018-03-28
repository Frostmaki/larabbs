<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthorizationsController extends Controller
{
    //
    public function store(AuthorizationRequest $request){
        $credentials = request(['email', 'password']);

        if(!$token=Auth::guard('api')->attempt($credentials)){
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->responseWithToken($token);
    }

    public function update(){
        return $this->responseWithToken(Auth::guard('api')->refresh());
    }

    public function destory(){
        Auth::guard('api')->logout();
        return $this->response->array(['message'=>'successfully logged out']);
    }

    public function responseWithToken($token){
        return response()->json([
            'access_token'  =>  $token,
            'token_type'    =>  'Bearer',
            'expires_in'    => Auth::guard('api')->factory()->getTTL()*60
        ])->setStatusCode(201);
    }
}
