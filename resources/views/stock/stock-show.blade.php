@extends("layouts.main")

@section('content')


@php

$tipoProduto = $product->type ?? $product[0]->type;



@endphp

<div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-[80vw] h-[80vh] bg-white shadow-lg rounded-lg p-8 flex flex-col items-center">

        <div class="flex justify-end w-full mb-5">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Editar</button>
        </div>
        @if ($tipoProduto !== 'variation')
        <div class="flex flex-col items-center">
            <img src="{{ $product->images[0]->src }}" alt="{{ $product->name }}"
                class="w-1/2 h-auto object-contain mb-6 rounded shadow">
            <input type="text" readonly value="{{ $product->name }}"
                class="text-center text-xl font-semibold border border-gray-300 p-3 rounded w-full mb-4">
        </div>




        <div class="flex flex-row justify-between w-50% space-x-10 mt-10">
            <div class="flex flex-col w-1/2">
                <label for="sku" class="text-gray-700 mb-2">Identificador de Produto</label>
                <input id="sku" type="text" readonly value="{{ $product->sku }}"
                    class="text-center border border-gray-300 p-3 rounded w-full">
            </div>

            <div class="flex flex-col w-1/2">
                <label for="price" class="text-gray-700 mb-2">Preço</label>
                <input id="price" type="text" readonly value="{{ $product->price }}"
                    class="text-center border border-gray-300 p-3 rounded w-full">
            </div>
        </div>

        @else
        @foreach ( $product as $variant )


        <button class="toggleBtn w-full mt-4 px-4 py-2  bg-green-500 text-white rounded hover:bg-green-600 transition">
            {{$variant->name}}
        </button>
        <div class=" content  hidden w-64 h-64  flex items-center justify-center text-lg fade-in">
            <div class="flex flex-col items-center">
                <img src="{{ $variant->image->src }}" alt="{{ $variant->name }}"
                    class="w-1/2 h-auto object-contain mb-6 rounded shadow">
                <input type="text" readonly value="{{ $variant->name }}"
                    class="text-center text-xl font-semibold border border-gray-300 p-3 rounded w-full mb-4">
            </div>




            <div class="flex flex-row justify-between w-50% space-x-10 mt-10">
                <div class="flex flex-col w-1/2">
                    <label for="sku" class="text-gray-700 mb-2">Identificador de Produto</label>
                    <input id="sku" type="text" readonly value="{{ $variant->sku }}"
                        class="text-center border border-gray-300 p-3 rounded w-full">
                </div>

                <div class="flex flex-col w-1/2">
                    <label for="price" class="text-gray-700 mb-2">Preço</label>
                    <input id="price" type="text" readonly value="{{ $variant->price }}"
                        class="text-center border border-gray-300 p-3 rounded w-full">
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

</div>





< <script>
    // Seleciona todos os botões com a classe toggleBtn
    const toggleButtons = document.querySelectorAll('.toggleBtn');

    toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
    // Encontra a div content que é irmã do botão clicado
    const contentDiv = this.nextElementSibling;

    // Alterna a classe hidden
    contentDiv.classList.toggle('hidden');
    });
    });
    </script>
    @endsection