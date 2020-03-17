<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addGroup('/v1/', function () {
    Router::post('user/login', 'App\Controller\AdminUserController@login');
    Router::post('cache_clear', 'App\Controller\IndexController@cacheClear');
    Router::addGroup('', function () {
        //新接口开始
        //新接口结束
        //公共接口
        Router::post('user/logout', 'App\Controller\AdminUserController@logout');
        Router::post('welcome', 'App\Controller\IndexController@welcome');
        Router::post('upload_token', 'App\Controller\IndexController@qnToken');
        Router::post('my_info/update', 'App\Controller\AdminUserController@updateMyInfo');
        Router::addGroup('admin_menu/', function () {
            Router::post('store', 'App\Controller\AdminMenuController@store');
            Router::post('update', 'App\Controller\AdminMenuController@update');
            Router::post('delete', 'App\Controller\AdminMenuController@delete');
            Router::post('index', 'App\Controller\AdminMenuController@index');
            Router::post('sort', 'App\Controller\AdminMenuController@sort');
            Router::get('detail/{id:\d+}', 'App\Controller\AdminMenuController@detail');
        });
        Router::addGroup('admin_user/', function () {
            Router::post('store', 'App\Controller\AdminUserController@store');
            Router::post('update', 'App\Controller\AdminUserController@update');
            Router::post('delete', 'App\Controller\AdminUserController@delete');
            Router::post('status', 'App\Controller\AdminUserController@status');
            Router::post('index', 'App\Controller\AdminUserController@index');
            Router::get('detail/{id:\d+}', 'App\Controller\AdminUserController@detail');
        });
        Router::addGroup('admin_role/', function () {
            Router::post('store', 'App\Controller\AdminRoleController@store');
            Router::post('update', 'App\Controller\AdminRoleController@update');
            Router::post('delete', 'App\Controller\AdminRoleController@delete');
            Router::post('index', 'App\Controller\AdminRoleController@index');
            Router::get('detail/{id:\d+}', 'App\Controller\AdminRoleController@detail');
        });
        Router::addGroup('admin_permission/', function () {
            Router::post('store', 'App\Controller\AdminPermissionController@store');
            Router::post('update', 'App\Controller\AdminPermissionController@update');
            Router::post('delete', 'App\Controller\AdminPermissionController@delete');
            Router::post('index', 'App\Controller\AdminPermissionController@index');
            Router::get('detail/{id:\d+}', 'App\Controller\AdminPermissionController@detail');
        });
        Router::addGroup('user/', function () {
            Router::post('index', 'App\Controller\UserController@index');
            Router::post('status', 'App\Controller\UserController@status');
            Router::get('detail/{id:\d+}', 'App\Controller\UserController@detail');
            Router::post('delete', 'App\Controller\UserController@delete');
        });
        Router::addGroup('version/', function () {
            Router::post('index', 'App\Controller\VersionController@index');
            Router::post('store', 'App\Controller\VersionController@store');
            Router::post('update', 'App\Controller\VersionController@update');
            Router::get('detail/{id:\d+}', 'App\Controller\VersionController@detail');
            Router::post('status', 'App\Controller\VersionController@status');
        });
        Router::addGroup('admin_operation_log/', function () {
            Router::post('index', 'App\Controller\AdminOperationLogController@index');
        });
    }, [
        'middleware' => [
            App\Middleware\Auth\CheckLoginMiddleware::class,
            App\Middleware\Auth\CheckPermissionMiddleware::class,
        ]//验证登录和token
    ]);
});
//Router::get($uri, $callback);
//Router::post($uri, $callback);
//Router::put($uri, $callback);
//Router::patch($uri, $callback);
//Router::delete($uri, $callback);
//Router::head($uri, $callback);