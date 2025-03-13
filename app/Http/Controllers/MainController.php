<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(6);
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
