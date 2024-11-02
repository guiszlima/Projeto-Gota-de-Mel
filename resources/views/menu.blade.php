@extends('layouts.main')

@section('content')
{{-- Mensagem de sucesso exibida acima dos itens --}}
@if (session('mensagem'))
<div class="flex justify-center">
    <div class=" mt-2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 w-full max-w-3xl"
        role="alert">
        <span class="block sm:inline">{{ session('mensagem') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <title>Fechar</title>
                <path
                    d="M14.348 5.652a.5.5 0 010 .707L11.707 9l2.641 2.641a.5.5 0 11-.707.707L11 9.707l-2.641 2.641a.5.5 0 11-.707-.707L9.293 9 6.652 6.348a.5.5 0 11.707-.707L11 8.293l2.641-2.641a.5.5 0 01.707 0z" />
            </svg>
        </button>
    </div>
</div>
@endif

{{-- Menu de opções --}}
<div class="flex justify-center flex-wrap space-x-4 mt-10">
    <x-menu-option titulo="Vender" img_title="Vender" alt="Vender" image="{{ asset('images/shopping-cart.png') }}"
        routeName="products.sell" button_text="Vender">
    </x-menu-option>

    <x-menu-option titulo="Código de Barras" img_title="Código de Barras" alt="Código de Barras"
        image="{{ asset('images/codigo_de_barras.jpg') }}" routeName="barcode.index" button_text="Código de barras">
    </x-menu-option>

    @if ($user->role_id === 3 || $user->role_id === 2)
    <x-menu-option titulo="Produtos" img_title="Produtos" alt="Produtos" image="{{ asset('images/produtos.jpg') }}"
        routeName="stock.index" button_text="Produtos">
    </x-menu-option>
    @endif
</div>
@endsection