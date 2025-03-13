<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductsFilterRequest;
use App\Product;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(ProductsFilterRequest $request)
    {
        $productQuery = Product::query();

        if($request->filled('price_from')){
            $productQuery->where('price', '>=', $request->price_from);
        }

        if($request->filled('price_to')){
            $productQuery->where('price', '<=', $request->price_to);
        }

        foreach(['hit','new','recommend'] as $field){
            if($request->has($field)){
                $productQuery->where($field, 1);
            }
        }
        
        $products = $productQuery->paginate(3)->withPath("?" . $request->getQueryString());
        return view('index', compact('products'));
    }
    public function categories()
    {
        $categories = Category::get();
        
        return view('categories', compact('categories'));
    }
    public function category($code)
    {
        $category = Category::where('code', $code)->first();

        return view('category', compact('category'));
    }
    // public function product($category, $product = null)
    // {
    //     dd($product);
    //     return view('product', compact('product'));
    // }

    public function product($category, $product = null)
    {
        $productBase = Product::where('code', $product)->first();
        //dd($productBase);
        return view('product', compact('productBase','product'));
    }


}
