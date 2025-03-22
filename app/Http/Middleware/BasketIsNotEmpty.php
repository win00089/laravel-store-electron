<?php

namespace App\Http\Middleware;


use Closure;

class BasketIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $order = session('order');
        if (!is_null($order) && $order->getFullSum() > 0) {
                return $next($request);    
        }
        session()->flash('warning', 'Ваша корзина пуста!');
        return redirect()->route('index');
    }
}
