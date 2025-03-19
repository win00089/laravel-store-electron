@extends('layouts.master')

@section('title', 'Товар')

@section('content')

<h1>{{$product->name}}</h1>
<h4>{{ $product->category->name }}</h4>
<p>Цена: <b>{{$product->price}}</b></p>
<img src="{{ Storage::url($product->image) }}">
<p>{{$product->description}}</p>

@if ($product->isAvailable())
<form action="{{route('basket-add', $product->id )}}" method="POST">
    <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
    @csrf
</form>
@else
<span>Товар не доступен</span>
<br>
<span>Сообщить мне, когда товар появится в наличии:</span>

@if ($errors->get('email'))
<br>
<br>
<span class="alert alert-danger" style="width: 300px; margin-bottom: 10px;">
    {{ $errors->get('email')[0] }}

</span>
<br>
<br>
@endif

<form method="POST" action="{{ route('subscription', $product) }}">
    @csrf
    <input type="text" name="email">
    <button type="submit" class="btn btn-success">Отправить</button>
</form>
@endif
@endsection