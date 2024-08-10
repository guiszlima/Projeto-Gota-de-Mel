@extends('layouts.main')

@section('content')


<div class="flex justify-center flex-wrap space-x-4 mt-10">
    <!-- Card 1 -->
    <x-menu-option titulo="Vender" img_title="Vender" alt="Vender" image="{{asset('images/shopping-cart.png')}}"
        routeName="products.sell" button_text="Vender"></x-menu-option>

    <x-menu-option titulo="Código de Barras" img_title="Código de Barras" alt="Código de Barras"
        image="{{asset('images/shopping-cart.png')}}" routeName="barcode.index" button_text="Código de barras">
    </x-menu-option>

    @if ($user->role_id === 3|| $user->role_id === 2)
    <x-menu-option titulo="Produtos" img_title="Produtos" alt="Produtos" image="{{asset('images/shopping-cart.png')}}"
        routeName="stock.index" button_text="Produtos">
    </x-menu-option>
    @endif

</div>

@endsection