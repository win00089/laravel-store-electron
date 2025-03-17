@extends('layouts.master')

@section('title', 'Товар')

@section('content')

<h1>{{$product->name}}</h1>
<h4>{{ $product->category->name }}</h4>
<p>Цена: <b>{{$product->price}}</b></p>
<img src="{{ Storage::url($product->image) }}">
<p>{{$product->description}}</p>
<form action="{{route('basket-add', $product->id )}}" method="POST">
    @if ($product->isAvailable())

    <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
    @else
    Не доступен
    @endif
    @csrf
</form>
@endsection