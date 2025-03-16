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
        if (!is_null($orderID)) {
            $order = Order::findOrFail($orderID);
        }
        if (is_null($orderID)) {
            return redirect()->route('index')->with('warning', 'Корзина пуста');
        }
        return view('basket', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $orderID = session('orderId');
        if (is_null($orderID)) {
            return redirect()->route('index');
        }
        $order = Order::find($orderID);
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
        if (is_null($orderID)) {
            return redirect()->route('index');
        }
        $order = Order::find($orderID);
        return view('order', compact('order'));
    }
    public function basketAdd($productId)
    {
        $orderID = session('orderId');
        if (is_null($orderID)) {
            $order = Order::create();
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderID);
        }

        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        } else {
            $order->products()->attach($productId);
        }

        if(Auth::check()){
            $order->user_id = Auth::id();
            $order->save();
        }

        $product = Product::find($productId);

        Order::changeFullSum($product->price);
        session()->flash('success', 'Добавлен товар ' . $product->name);

        return redirect()->route('basket');
    }
    public function basketRemove($productId)
    {
        $orderID = session('orderId');
        if (is_null($orderID)) {
            return redirect()->route('basket');
        }
        $order = Order::find($orderID);

        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            if ($pivotRow->count < 2) {
                $order->products()->detach($productId);
            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }
        }

        $product = Product::find($productId);
        Order::changeFullSum(-$product->price);
        session()->flash('warning', 'Удален товар ' . $product->name);

        return redirect()->route('basket');
    }
}
