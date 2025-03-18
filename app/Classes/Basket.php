<?php

namespace App\Classes;

use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;

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

  public function countAvaliable($updateCount = false)
  {
    foreach ($this->order->products as $orderProduct) {
      if ($orderProduct->count < $this->getPivotRow($orderProduct)->count) {
        return false;
      }
      if ($updateCount) {
        $orderProduct->count -= $this->getPivotRow($orderProduct)->count;
      }
    }
    if ($updateCount) {
      $this->order->products->map->save();
    }
    return true;
  }

  public function getOrder()
  {
    if (!$this->countAvaliable()) {
      return false;
    }
    return $this->order;
  }

  public function saveOrder($name, $phone,$email)
  {
    if(!$this->countAvaliable(true)){
      return false;
    }
    Mail::to($email)->send(new OrderCreated($name, $this->getOrder()));
    return $this->order->saveOrder($name, $phone);
  }

  protected function getPivotRow($product)
  {
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
      if ($pivotRow->count > $product->count) {
        return false;
      }
      $pivotRow->update();
    } else {
      if ($product->count == 0) {
        return false;
      }
      $this->order->products()->attach($product->id);
    }
    Order::changeFullSum($product->price);
    return true;
  }
}
