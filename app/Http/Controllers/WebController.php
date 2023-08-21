<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Models\Product;

class WebController extends Controller
{
    //
    public function index()
    {
        // $categories = Category::all()->sortBy('major_category_name');
        $categories = Category::all();

        //$major_category_names = Category::pluck('major_category_name')->unique();
        $major_categories = MajorCategory::all();
        //return view('web.index', compact('major_category_names', 'categories'));

        $recently_products = Product::orderBy('created_at', 'desc')->take(4)->get();
        //Product::orderBy('created_at', 'desc')->take(4)->get();とすることで、
        //商品の登録日時（created_at）でソートして、新しい順に4つ取得してビューに渡しています。

        $recommend_products = Product::where('recommend_flag', true)->take(3)->get();
        //おすすめフラグがONの商品を3つ取得してビューに渡しています。
        //
        return view('web.index', compact('major_categories', 'categories', 'recently_products', 'recommend_products'));
        //
        //
    }
}
