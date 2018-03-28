<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;


class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request)
    {
        $email=$request->email;

        //生成四位随机数
         $code=str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);

         //发邮件

        return $this->response->array(['test_message' => 'store verification code']);
    }
}
