<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ShoppingCart extends Model
{
    use HasFactory;
    protected $table = 'shoppingcart';#テーブルとの紐づけ


    /**
     * ユーザーIDを指定すると、該当ユーザーの注文一覧を取得する
     * getCurrentUserOrders()を追加しています。
     * 注文一覧として、注文ID、購入日時、金額、ユーザー名、注文番号を取得しています。
     */
    public static function getCurrentUserOrders($user_id)
     {
         $shoppingcarts = DB::table('shoppingcart')->where("instance", "{$user_id}")->get();
 
         $orders = [];
 
         foreach ($shoppingcarts as $order) {
             $orders[] = [
                 'id' => $order->number,//注文ID
                 'created_at' => $order->updated_at,//購入時間
                 'total' => $order->price_total,//金額
                 'user_name' => User::find($order->instance)->name,//ユーザーネーム
                 'code' => $order->code//注文番号
             ];
         }
 
         return $orders;
     }
}
