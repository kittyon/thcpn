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

              $api->get('user/search', 'UsersController@search')->name('api.user.search');
              $api->post('user/invite', 'UsersController@Invite')->name('api.user.invite');
              // 获取devices信息
              $api->get('devices','DevicesController@index')->name('api.devices.index');
              $api->post('devices/attach','DevicesController@attach')->name('api.devices.attach');
              $api->get('device/{device_id}','DevicesController@show')->name('api.device.show');
              $api->put('device/{device_id}', 'DevicesController@update')->name('api.device.update');
              $api->post('device/{device_id}/detach', 'DevicesController@detach')->name('api.device.detach');
              // 获取organization信息
              $api->get('organizations','OrganizationsController@index')->name('api.organizations.index');
              $api->post('organization','OrganizationsController@store')->name('api.organization.store');
              $api->get('organization/{org_id}','OrganizationsController@show')->name('api.organizations.show');
              $api->put('organization/{org_id}', 'OrganizationsController@update')->name('api.organizations.update');
              $api->get('organization/{org_id}/users', 'OrganizationsController@users')->name('api.organization.users');
              $api->post('organization/{org_id}/detach','OrganizationsController@detach')->name('api.organization.detach');
              $api->get('organization/{org_id}/children','OrganizationsController@children')->name('api.organization.children');
              // 获取permission信息
              $api->get('permissions','PermissionsController@index')->name('api.permissions.index');
              // 获取device最新的config
              $api->get('device/{device_id}/config','DeviceConfigController@last')->name('api.config.last');
              $api->get('device/{device_id}/config/{config_id}','DeviceConfigController@show')->name('api.config.show');
              // 更改device的config
              $api->post('device/{device_id}/config','DeviceConfigController@store')->name('api.config.store');
              // 获取device的data数据
              $api->get('device/{device_id}/datas/new/{data_type}','DeviceDataController@first')->name('api.deviceData.first');
              $api->get('device/{device_id}/datas','DeviceDataController@index')->name('api.deviceData.index');

              // 获取invitation信息
              $api->get('invitations/owner','InvitationController@indexOfOwner')->name('api.invitations.index.owner');
              $api->get('invitations/user','InvitationController@indexOfUser')->name('api.invitations.index.user');
              $api->post('invitation','InvitationController@store')->name('api.inivataion.store');
              $api->post('invitation/{inv_id}/pass','InvitationController@pass')->name('api.invitation.pass');
              $api->post('invitation/{inv_id}/unpass','InvitationController@unpass')->name('api.invitation.unpass');
              $api->delete('invitation/{inv_id}','InvitationController@delete')->name('api.invitation.delete');
              // notification 相关
              $api->get('notifications','NotificationController@index')->name('api.notifications.index');
              $api->get('notifications/all','NotificationController@all')->name('api.notifications.all');
              $api->post('notifications/{id}/read', 'NotificationController@markAsRead')->name('api.notification.markAsRead');
              $api->post('notifications/mark-all-read','NotificationController@markAllRead')->name('api.notification.markAllRead');
              $api->post('notifications','NotificationController@store')->name('api.notification.store');

              $api->get('downloads','DownloadController@index')->name('api.download.index');
              $api->post('download','DownloadController@store')->name('api.download.store');
              $api->delete('download','DownloadController@destroy')->name('api.download.destroy');
          });
      });

  $api->group([
    'middleware'=>'api.throttle',
    'limit'=>config('api.rate_limits.sign.limit'),
    'expires'=>config('api.rate_limits.sign.expires'),
  ],function($api){

    $api->post('reset/password','UsersController@resetPass')->name('api.users.resetPass');
    $api->post('reset/code','VerificationCodesController@resetPass')->name('api.verificationCodes.resetPass');
    // 短信验证码
    $api->post('verificationCodes/phone', 'VerificationCodesController@storeByPhone')
        ->name('api.verificationCodes.phone.store');
    $api->post('verificationCodes/email', 'VerificationCodesController@storeByEmail')
        ->name('api.VerificationCodes.email.store');
    // 用户注册
    $api->post('users', 'UsersController@store')
        ->name('api.users.store');
    // 图片验证码
    $api->post('captchas', 'CaptchasController@store')
        ->name('api.captchas.store');
    //$api->post('captchas/email', 'CaptchasController@storeByEmail')
    //    ->name('api.captchas.email.store');
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
