@extends('layouts.master')

@section('title', 'Товар')

@section('content')

    <h1>{{$product->name}}</h1>
    <h4>{{ $product->category->name }}</h4>
    <p>Цена: <b>{{$product->price}}</b></p>
    <img src="{{ Storage::url($product->image) }}">
    <p>{{$product->description}}</p>
    @if($product->isAvailable())
    <a class="btn btn-success" href="{{ route('basket-add', $product) }}">Добавить в корзину</a>
    @else
    Не доступен
    @endif
@endsection