<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket()
    {
        $orderID = session('orderId');
        
            $order = Order::findOrFail($orderID);
        
       
        return view('basket', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $orderID = session('orderId');
        $order = Order::findOrFail($orderID);
        $success = $order->saveOrder($request->name, $request->phone);

        if($success){
            session()->flash('success', 'Ваш заказ принят в обработку');
        }else{
            session()->flash('warning', 'Случилась ошибка');
        }
        Order::eraseOrderSum();
        return redirect()->route('index');
    }
    public function basketPlace()
    {
        $orderID = session('orderId');
        $order = Order::findOrFail($orderID);
        return view('order', compact('order'));
    }
    public function basketAdd(Product $product)
    {

        
        $orderID = session('orderId');
        if (is_null($orderID)) {
            $order = Order::create();
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderID);
        }

        if ($order->products->contains($product->id)) {
            $pivotRow = $order->products()->where('product_id', $product->id)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        } else {
            $order->products()->attach($product->id);
        }

        if(Auth::check()){
            $order->user_id = Auth::id();
            $order->save();
        }

        Order::changeFullSum($product->price);
        session()->flash('success', 'Добавлен товар ' . $product->name);

        return redirect()->route('basket');
    }
    public function basketRemove(Product $product)
    {
        $orderID = session('orderId');
        $order = Order::findOrFail($orderID);
        if ($order->products->contains($product->id)) {
            $pivotRow = $order->products()->where('product_id', $product->id)->first()->pivot;
            if ($pivotRow->count < 2) {
                $order->products()->detach($product->id);
            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }
        }
        Order::changeFullSum(-$product->price);
        session()->flash('warning', 'Удален товар ' . $product->name);

        return redirect()->route('basket');
    }
}
