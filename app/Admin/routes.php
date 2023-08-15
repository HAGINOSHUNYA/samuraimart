<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\CategoryController;//カテゴリコントローラとの紐づけUSE宣言
use App\Admin\Controllers\ProductController;//商品コントローラとの紐づけUSE宣言
use App\Admin\Controllers\MajorCategoryController;//親カテゴリーとの紐づけ
use App\Admin\Controllers\UserController;//ユーザー管理の紐づけ
Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('categories', CategoryController::class);//カテゴリ管理画面のルーティング
    $router->resource('products', ProductController::class);//商品の管理画面のルーティング
    $router->resource('major-categories', MajorCategoryController::class);//親カテゴリ画面のルーティング
    $router->resource('users', UserController::class);//ユーザー管理



});
