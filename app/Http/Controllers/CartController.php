<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
         $has_carriage_cost = false;//送料フラグデフォルトfalse
         $carriage_cost = 0;//送料デフォルト0
 
         foreach ($cart as $c) {
            //購入した商品（カート内の全ての商品）のうち、一つでも送料ありの商品があるかどうかを判断し、
            //送料ありの商品があった時だけ$has_carriage_costフラグをtrueにしておきます。
             $total += $c->qty * $c->price;
             if ($c->options->carriage) {
                $has_carriage_cost = true;
            }
         }
         if($has_carriage_cost) {
            //if($has_carriage_cost)により送料ありの商品があったかどうかを判断し、
            //あった時だけ商品合計金額（$total）に送料800円を追加しています。
            $total += env('CARRIAGE');
            $carriage_cost = env('CARRIAGE');
            //、送料も別途画面に表示したいため、$carriage_costに800円を設定しておきます。
            //送料800円はenv('CARRIAGE')で.envファイルから取得しています。
         }
 
                return view('carts.index', compact('cart', 'total', 'carriage_cost'));
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
                'options' => [
                    'image' => $request->image,//画像のパスをCartインスタンスにaddしています。
                    'carriage' => $request->carriage,//formから送信された送料の有無をカートに保存しています。
                ]
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
        /*
       * $user_shoppingcarts = DB::table('shoppingcart')->where('instance', Auth::user()->id)->get();
       * $count = $user_shoppingcarts->count();//現在までのユーザーが注文したカートの数を取得しています。
 
       * $count += 1;新しくデータベースに登録するカートのデータ用にカートのIDを一つ増やしています
       * Cart::instance(Auth::user()->id)->store($count);//ユーザーのIDを使ってカート内の商品情報などをデータベースへと保存しています。
       * dd("stop");
       * DB::table('shoppingcart')->where('instance', Auth::user()->id)->where('number', null)->update(['number' => $count, 'buy_flag' => true]);
       * 購入済みフラグをtrueにして、購入処理を行っています。
       * DB::table('shoppingcart')では、データベース内のshoppingcartテーブルへのアクセスを行っています。
       * その後where()を使ってユーザーのIDとカート数$countを使い、先ほど作成したカートのデータを更新しています。   
        **/

        $user_shoppingcarts = DB::table('shoppingcart')->get();
        /** 
         * shoppingcartテーブルからの全行の取得し$user_shoppingcartsに格納
         * $user_shoppingcarts = shoppingcart::all();
         * use App\Http\Models\shoppingcart;
         * モデル作る
         * */
        


        $number = DB::table('shoppingcart')->where('instance', Auth::user()->id)->count();
        /**
         * shoppingcartテーブルのinstanceの中からAuth::user()->id
         * と一致するものをcount()関数でカウント
         * $numberに格納
         */

        $count = $user_shoppingcarts->count();
        #「$user_shoppingcrats」でカウントしたものを「$count」に格納

        $count += 1;#$count = $count+1
        $number += 1;#$number = $number+1　　
        $cart = Cart::instance(Auth::user()->id)->content();

        $price_total = 0;
        $qty_total = 0;
        $has_carriage_cost = false;

        foreach ($cart as $c) {#$cart->$c
            $price_total += $c->qty * $c->price;
            $qty_total += $c->qty;
            if ($c->options->carriage) {#もしoptionの中の（65行目）carriageに値があったら（1）
        
                $has_carriage_cost = true;
                #$has_carriage_cost(送料フラグ)をtrue（1）にする
            }
        }

        if($has_carriage_cost) {#もし$has_carringage_costに値があったら（true,1）
            $price_total += env('CARRIAGE');
            #合計金額に送料（.envファイルの$CARRIAGE=800）を加算する
        }

        Cart::instance(Auth::user()->id)->store($count);
        /**
         * 
         */

        DB::table('shoppingcart')->where('instance', Auth::user()->id)
            ->where('number', null)
            ->update(
                [
                    'code' => substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10),
                    'number' => $number,
                    'price_total' => $price_total,
                    'qty' => $qty_total,
                    'buy_flag' => true,
                    'updated_at' => date("Y/m/d H:i:s")
                ]
            );

         Cart::instance(Auth::user()->id)->destroy();
 
         return to_route('carts.index');
       
    }
}
