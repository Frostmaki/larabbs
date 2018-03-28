<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchasRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;


class CaptchasController extends Controller
{
    //
    public function store(CaptchasRequest $captchasRequest,CaptchaBuilder $captchaBuilder){

        $key='Captcha-'.str_random(15);

        $email=$captchasRequest->email;

        $captcha=$captchaBuilder->build();

        $expiredAt=now()->addMinute(2);

        \Cache::put($key,['email'=>$email,'code'=>$captcha->getPhrase()],$expiredAt);

        $result=[
            'Captcha_key'=>$key,
            'expired_at'=>$expiredAt->toDateTimeString(),
            'Captcha_image_content'=>$captcha->inline(),
        ];

        return $this->response->array($result)->setStatusCode(201);

    }
}
