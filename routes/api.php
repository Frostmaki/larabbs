<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {
    // 验证码
    $api->post('VCode', 'VerificationCodesController@store');

    $api->get('user','UsersController@index');
    $api->get('user/{id}','UsersController@show');

    //用户注册
    $api->post('users','UsersController@store');

    //图片验证码
    $api->post('captchas','CaptchasController@store');
});

