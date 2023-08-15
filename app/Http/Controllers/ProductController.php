<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->category !== null) {//リクエストに入っているcategoryの中がnullでなければ
            $products = Product::where('category_id', $request->category)->sortable()->paginate(15);
            //where(検索対象カラム,検索数値)?->ソート->ページネーション（ページ数）
            $total_count = Product::where('category_id', $request->category)->count();
            //当該カテゴリーの商品数を表示するため
            $category = Category::find($request->category);//カテゴリー名を取得します。
            $major_category = MajorCategory::find($category->major_category_id);


        } else {
            $products = Product::sortable()->paginate(15);
            $total_count = "";
            $category = null;
            $major_category = null; 
        }
        $categories = Category::all();
        $major_categories = MajorCategory::all();
         //$major_category_names = Category::pluck('major_category_name')->unique();
         //全カテゴリーのデータからmajor_category_nameのカラムのみを取得します。
         //その上でunique()を使い、重複している部分を削除しています。
        dump($categories);
         return view('products.index', compact('products', 'category', 'major_category', 'categories', 'major_categories', 'total_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
  
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->save();

        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        $reviews = $product->reviews()->get();
  
        return view('products.show', compact('product', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        $categories = Category::all();
  
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->update();

        return to_route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();
  
        return to_route('products.index');
    }

    public function favorite(Product $product)
    {
        Auth::user()->togglefavorite($product);

        return back();
    }
}
