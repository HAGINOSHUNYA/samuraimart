<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
 use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //カート内表示
        $cart = Cart::instance(Auth::user()->id)->content();
 
         $total = 0;
 
         foreach ($cart as $c) {
             $total += $c->qty * $c->price;
         }
 
         return view('carts.index', compact('cart', 'total'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //カート内の追加
        Cart::instance(Auth::user()->id)->add(
            [
                'id' => $request->id, 
                'name' => $request->name, 
                'qty' => $request->qty, 
                'price' => $request->price, 
                'weight' => $request->weight, 
            ] 
        );

        return to_route('products.show', $request->get('id'));
    }
  

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $user_shoppingcarts = DB::table('shoppingcart')->where('instance', Auth::user()->id)->get();
         $count = $user_shoppingcarts->count();//現在までのユーザーが注文したカートの数を取得しています。
 
         $count += 1;//新しくデータベースに登録するカートのデータ用にカートのIDを一つ増やしています
         Cart::instance(Auth::user()->id)->store($count);//ユーザーのIDを使ってカート内の商品情報などをデータベースへと保存しています。
 
         DB::table('shoppingcart')->where('instance', Auth::user()->id)->where('number', null)->update(['number' => $count, 'buy_flag' => true]);
        //購入済みフラグをtrueにして、購入処理を行っています。
        //DB::table('shoppingcart')では、データベース内のshoppingcartテーブルへのアクセスを行っています。
        //その後where()を使ってユーザーのIDとカート数$countを使い、先ほど作成したカートのデータを更新しています。   

         Cart::instance(Auth::user()->id)->destroy();
 
         return to_route('carts.index');
       
    }
}