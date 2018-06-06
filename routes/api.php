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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', function($api) {
    $api->get('version', function() {
        return response('this is version v1');
    });
});

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => 'serializer:array'
], function($api) {
  $api->group([
          'middleware' => 'api.throttle',
          'limit' => config('api.rate_limits.access.limit'),
          'expires' => config('api.rate_limits.access.expires'),
      ], function ($api) {
          // 游客可以访问的接口

          // 需要 token 验证的接口
          $api->group(['middleware' => 'api.auth'], function($api) {
              // 当前登录用户信息
              $api->get('user', 'UsersController@me')
                  ->name('api.user.show');
              // 获取devices信息
              $api->get('devices','DevicesController@index')->name('api.devices.index');
              $api->get('device/{device_id}','DevicesController@show')->name('api.device.show');
              $api->put('device/{device_id}', 'DevicesController@update')->name('api.device.update');
              $api->post('device/{device_id}/dettach', 'DevicesController@detach')->name('api.device.detach');
              // 获取organization信息
              $api->get('organizations','OrganizationsController@index')->name('api.organizations.index');
              // 获取permission信息
              $api->get('permissions','PermissionsController@index')->name('api.permissions.index');
              // 获取device最新的config
              $api->get('device/{device_id}/config','DeviceConfigController@last')->name('api.config.last');
              $api->get('device/{device_id}/config/{config_id}','DeviceConfigController@show')->name('api.config.show');
              // 更改device的config
              $api->post('device/{device_id}/config','DeviceConfigController@store')->name('api.config.store');
              // 获取device的data数据
              $api->get('device/{device_id}/datas','DeviceDataController@index')->name('api.deviceData.index');

          });
      });

  $api->group([
    'middleware'=>'api.throttle',
    'limit'=>config('api.rate_limits.sign.limit'),
    'expires'=>config('api.rate_limits.sign.expires'),
  ],function($api){
    // 短信验证码
    $api->post('verificationCodes', 'VerificationCodesController@store')
        ->name('api.verificationCodes.store');
    // 用户注册
    $api->post('users', 'UsersController@store')
        ->name('api.users.store');
    // 图片验证码
    $api->post('captchas', 'CaptchasController@store')
        ->name('api.captchas.store');
    // 第三方登录
    $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
          ->name('api.socials.authorizations.store');
    });
    // 登录
    $api->post('authorizations', 'AuthorizationsController@store')
    ->name('api.authorizations.store');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('api.authorizations.destroy');
});
