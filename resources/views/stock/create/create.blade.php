@extends('layouts.main')

@section('content')
<main class="flex flex-col">
    <h1 class="text-2xl font-bold m-6">Criar</h1>
    <div class="flex flex-row justify-around w-1/3 my-10">
        <x-dynamic-link text="Produto" route="stock.create" currentRoute="{{$currentRoute}}" />
        <x-dynamic-link text="Atributo" route="stock.attribute" currentRoute="{{$currentRoute}}" />
    </div>
    <div class="w-full max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <form action="{{route('stock.store')}}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nome</label>
                <input type="text" id="name" name="name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite o nome do produto">
            </div>

            <div class="mb-4">
                <label for="sku" class="block text-gray-700 font-bold mb-2">Identificador de Produto</label>
                <input type="text" id="sku" name="sku"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite o SKU">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-bold mb-2">Preço</label>
                <input type="text" id="price" name="price"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite o preço">
            </div>

            <div class="mb-4">
                <label for="stock_quantity" class="block text-gray-700 font-bold mb-2">Quantidade em Estoque</label>
                <input type="number" id="stock_quantity" name="stock_quantity"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite a quantidade em estoque">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Salvar Produto
                </button>
            </div>
        </form>
    </div>
</main>
@endsection