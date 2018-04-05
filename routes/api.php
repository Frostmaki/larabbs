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
    'namespace' => 'App\Http\Controllers\Api',
     'middleware' => 'serializer:array'
], function($api) {
    // 验证码
    $api->post('VCode', 'VerificationCodesController@store');


    //用户注册
    $api->post('users','UsersController@store');

    //图片验证码
    $api->post('captchas','CaptchasController@store');

    $api->group([
        'middleware'    =>  'api.throttle',
        'limit'     =>  config('api.rate_limits.access.limit'),
        'expires'   =>  config('api.rate_limits.access.expires'),
    ],function ($api){
        //游客可以访问的接口
        //生成Token
        $api->post('authorizations','AuthorizationsController@store')
            ->name('api.authorizations.store');
        // 刷新token
        $api->put('authorizations/current','AuthorizationsController@update')
            ->name('api.authorizations.update');
        //删除Token
        $api->delete('authorizations/current','AuthorizationsController@destory')
            ->name('api.authorizations.destroy');

        //分类列表
        $api->get('categories','CategoriesController@index')
            ->name('api.category.index');

        //帖子列表
        $api->get('topics','TopicsController@index')
            ->name('api.topics.index');

        //帖子详情
        $api->get('topics/{id}', 'TopicsController@show')
            ->name('api.topics.show');



//<!-----------------------------------------------------------------------------------------!-->
        //需要token验证的接口
        $api->group(['middleware'=> 'api.auth'],function ($api){
            //当前用户登录信息
            $api->get('user','UsersController@me')
                ->name('api.user.show');

            //图片资源
            $api->post('images','ImagesController@store')
                ->name('api.images.store');

            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');

            //创建帖子
            $api->post('topic','TopicsController@store')
                ->name('api.topics.store');

            //修改帖子
            $api->patch('topics/{id}','TopicsController@update')
                ->name('api.topics.update');

            //删除帖子
            $api->delete('topics/{id}', 'TopicsController@destroy')
                ->name('api.topics.destroy');

            // 发布回复
            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');

            // 删除回复
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                ->name('api.topics.replies.destroy');
        });
    });
});

