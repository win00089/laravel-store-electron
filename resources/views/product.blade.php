@extends('layouts.master')

@section('title', 'Товар')

@section('content')

    <h1>iPhone X 64GB</h1>
    <h4>{{ $product }}</h4>
    <p>Цена: <b>71990 руб.</b></p>
    <img src="http://laravel.store.electron/storage/{{ $productBase->image }}">
    <p>Отличный продвинутый телефон с памятью на 64 gb</p>
    <a class="btn btn-success" href="http://laravel.store.electron/basket/1/add">Добавить в корзину</a>

@endsection