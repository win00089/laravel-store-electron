@extends('layouts.master')

@section('title', 'Товар')

@section('content')

<h1>{{$sku->product->__('name')}}</h1>
<h4>{{ $sku->product->category->name }}</h4>
<p>Цена: <b>{{$sku->price}} {{ $currencySymbol }}</b></p>
<img src="{{ Storage::url($sku->product->image) }}">
<p>{{$sku->product->__('description')}}</p>

@if ($sku->isAvailable())
<form action="{{route('basket-add', $sku->product->id )}}" method="POST">
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

<form method="POST" action="{{ route('subscription', $sku) }}">
    @csrf
    <input type="text" name="email">
    <button type="submit" class="btn btn-success">Отправить</button>
</form>
@endif
@endsection