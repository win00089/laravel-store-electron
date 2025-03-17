<?php

namespace App\Classes;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Basket
{
  protected $order;

  public function __construct($createOrder = false)
  {
    $orderId = session('orderId');
    if (is_null($orderId) && $createOrder) {
      $data = [];
      if (Auth::check()) {
        $data['user_id'] = Auth::id();
      }
      $this->order = Order::create($data);
      session(['orderId' => $this->order->id]);
    } else {
      $this->order = Order::findOrFail($orderId);
    }
  }

  public function getOrder()
  {
    return $this->order;
  }

  public function saveOrder($name, $phone)
  {
    return $this->order->saveOrder($name, $phone);
  }

  protected function getPivotRow($product){
    return $this->order->products()->where('product_id', $product->id)->first()->pivot;
  }

  public function removeProduct(Product $product)
  {
    if ($this->order->products->contains($product->id)) {
      $pivotRow = $this->getPivotRow($product);
      if ($pivotRow->count < 2) {
        $this->order->products()->detach($product->id);
      } else {
        $pivotRow->count--;
        $pivotRow->update();
      }
    }
  }

  public function addProduct(Product $product)
  {
    if ($this->order->products->contains($product->id)) {
      $pivotRow = $this->getPivotRow($product);
      $pivotRow->count++;
      $pivotRow->update();
    } else {
      $this->order->products()->attach($product->id);
    }

    Order::changeFullSum($product->price);
  }
}
