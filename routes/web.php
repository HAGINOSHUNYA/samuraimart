<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(CartController::class)->group(function () {//コントローラまとめ
    Route::get('users/carts', 'index')->name('carts.index');//カート内表示
    Route::post('users/carts', 'store')->name('carts.store');//カートに入れる機能
    Route::delete('users/carts', 'destroy')->name('carts.destroy');//削除？
});

Route::controller(UserController::class)->group(function () {//コントローラまとめ
    Route::get('users/mypage', 'mypage')->name('mypage');
    Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
    Route::put('users/mypage', 'update')->name('mypage.update');
    Route::get('users/mypage/password/edit', 'edit_password')->name('mypage.edit_password');//パスワード変更ページ
    Route::put('users/mypage/password', 'update_password')->name('mypage.update_password'); //パスワード変更機能
    Route::get('users/mypage/favorite', 'favorite')->name('mypage.favorite');//お気に入りの表示

});


Route::get('products/{product}/favorite', [ProductController::class, 'favorite'])->name('products.favorite');//お気に入り登録
Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::resource('products', ProductController::class)->middleware(['auth', 'verified']);
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
