@extends("layouts.main")

@section('content')
<x-button-back :route="route('menu')"></x-button-back>

<div class="container mx-auto px-4 py-8">
<form method="GET" action="{{ route('stock.index') }}" class="flex flex-wrap md:flex-nowrap gap-2 items-center w-full">
    <input 
        id="searchTerm" 
        name="busca"
        type="text" 
        placeholder="Pesquisar produto..." 
        value="{{ request('busca') }}"
        class="px-4 py-2 border border-gray-300 rounded-md w-full md:flex-1 focus:outline-none focus:ring-2 focus:ring-yellow-400" 
    />

    <select name="searchType" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <option value="id" {{ request('searchType') === 'id' ? 'selected' : '' }}>ID</option>
        <option value="name" {{ request('searchType', 'name') === 'name' ? 'selected' : '' }}>Nome</option>
        <option value="sku" {{ request('searchType') === 'sku' ? 'selected' : '' }}>Código de Barras</option>
    </select>

    <button 
        type="submit"
        class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition-all duration-300"
    >
        Pesquisar
    </button>

    <button 
    type="button"
    id="limpar"
    class="bg-red-500 text-white px-6 py-2 rounded-2xl shadow-md hover:bg-red-600 hover:shadow-lg transition-all duration-300 font-medium"
>
    Limpar
</button>
</form>


    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Preço</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Gerar Código
                    de Barras</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($products as $product)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
             {{ $product->variations === true ? 'Variação' : 'R$ ' . number_format((float)$product->price, 2, ',', '.') }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <form action="{{ route('barcode.generate') }}">
                        @csrf
                      
                        <input type="hidden" name="sku" value="{{ $product->sku }}">
                        <input type="hidden" name="variations" value="{{ $product->variations }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="id" value="{{$product->id}}">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Gerar Código de Barras
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6 flex justify-center">
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
</div>


@endsection