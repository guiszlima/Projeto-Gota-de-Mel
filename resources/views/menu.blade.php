@extends('layouts.main')

@section('content')


<div class="flex justify-center flex-wrap space-x-4 mt-10">
    <!-- Card 1 -->
   <x-menu-option titulo="Vender" img_title="Vender" alt="Vender" image="{{asset('images/shopping-cart.png')}}"></x-menu-option>
    <!-- Card 2 -->
    <div id="whoobe-3fery" class="w-full md:w-64 justify-center items-center bg-white shadow-lg rounded-lg flex flex-col">
        <img src="https://res.cloudinary.com/moodgiver/image/upload/v1633344243/adventure_woman_rujic1.webp" alt="img" title="img" class="w-full h-auto object-cover rounded-t-lg" id="whoobe-ixxe5">
        <div id="whoobe-1okdg" class="w-full p-4 justify-start flex flex-col">
            <h4 class="border-b-2 text-3xl text-center" id="whoobe-3mr7n">Vender</h4>
            <button value="button" class="my-4 px-4 py-2 text-white hover:bg-blue-700 bg-blue-500" id="whoobe-jkkr2">Ir para página</button>
        </div>
    </div>
</div>

@endsection