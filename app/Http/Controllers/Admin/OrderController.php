<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::active()->with('currency')->paginate(5);
        
        return view('auth.orders.index', compact('orders'));
        // короче копировать файлы с гит 30 урока чтобы убрать ошибку если что откатиться к коммиту последнему (refactor)
    }

    public function show(Order $order)
    {
        $products = $order->products()->withTrashed()->get();
        return view('auth.orders.show', compact('order', 'products'));
    }
}
