<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;


class UsersController extends Controller
{
    //
    public function index(){
        return User::all();
    }
    public function show($id){

       $user= User::find($id);
       return $this->response->array($user->toArray());
    }

    public function store(UserRequest $userRequest){
        $user=User::create([
            'name'=>$userRequest->name,
            'email'=>$userRequest->email,
            'password'=>bcrypt($userRequest->password),
        ]);

        return $this->response->created();
    }

}
