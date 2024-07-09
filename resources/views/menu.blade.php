@extends('layouts.main')

@section('content')


<div class="flex justify-center flex-wrap space-x-4 mt-10">
    <!-- Card 1 -->
   <x-menu-option titulo="Vender" img_title="Vender" alt="Vender" image="{{asset('images/shopping-cart.png')}}" routeName="products.sell"></x-menu-option>
</div>

@endsection