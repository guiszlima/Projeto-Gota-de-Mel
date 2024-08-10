@extends("layouts.main")

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gerar Código de Barras</h1>

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
                <td class="px-6 py-4 whitespace-nowrap">{{ $product->price }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <form action="{{ route('barcode.generate') }}">
                        @csrf
                        <input type="hidden" name="sku" value="{{ $product->sku }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
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
        @if ($currentPage > 1)
        <a href="{{ url()->current() }}?page={{ $currentPage - 1 }}"
            class="mx-1 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Anterior</a>
        @endif

        @for ($i = 1; $i <= $totalPages; $i++) <a href="{{ url()->current() }}?page={{ $i }}"
            class="mx-1 px-3 py-1 {{ $i == $currentPage ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-gray-300">
            {{ $i }}
            </a>
            @endfor

            @if ($currentPage < $totalPages) <a href="{{ url()->current() }}?page={{ $currentPage + 1 }}"
                class="mx-1 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Próximo</a>
                @endif
    </div>
</div>
@endsection