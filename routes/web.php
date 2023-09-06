<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;//商品のコントローラ
use App\Http\Controllers\ReviewController;//レビューのコントローラ
use App\Http\Controllers\UserController;//ユーザーのコントローラ
use App\Http\Controllers\CartController;//カートのコントローラ
use App\Http\Controllers\WebController;//トップページのコントローラ


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

Route::get('/',  [WebController::class, 'index']);

Route::controller(CartController::class)->group(function () {//カートコントローラまとめ
    Route::get('users/carts', 'index')->name('carts.index');//カート内表示
    Route::post('users/carts', 'store')->name('carts.store');//カートに入れる機能
    Route::delete('users/carts', 'destroy')->name('carts.destroy');//削除？
});

Route::controller(UserController::class)->group(function () {//ユーザーコントローラまとめ
    Route::get('users/mypage', 'mypage')->name('mypage');
    Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
    Route::put('users/mypage', 'update')->name('mypage.update');
    Route::get('users/mypage/password/edit', 'edit_password')->name('mypage.edit_password');//パスワード変更ページ
    Route::put('users/mypage/password', 'update_password')->name('mypage.update_password'); //パスワード変更機能
    Route::get('users/mypage/favorite', 'favorite')->name('mypage.favorite');//お気に入りの表示
    Route::delete('users/mypage/delete', 'destroy')->name('mypage.destroy');
    Route::get('users/mypage/cart_history', 'cart_history_index')->name('mypage.cart_history');//履歴一覧
    Route::get('users/mypage/cart_history/{num}', 'cart_history_show')->name('mypage.cart_history_show');//履歴詳細
    Route::get('users/mypage/register_card', 'register_card')->name('mypage.register_card');//クレジットカード
     Route::post('users/mypage/token', 'token')->name('mypage.token');//クレジットカード
});


Route::get('products/{product}/favorite', [ProductController::class, 'favorite'])->name('products.favorite');//お気に入り登録
Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::resource('products', ProductController::class)->middleware(['auth', 'verified']);
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
