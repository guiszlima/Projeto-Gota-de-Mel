

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gerar Código de Barras</h1>
    <div class="mb-6 flex justify-between">
        <button wire:click="offSearch" id="limpar"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-all duration-300">
            Limpar
        </button>
        <div class="flex w-full ml-4">
            <input id="searchTerm" type="text" wire:model="searchTerm" placeholder="Pesquisar produto..."
                class="px-4 py-2 border border-gray-300 rounded-l-md w-full focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            <button wire:click="search"
                class="bg-yellow-500 text-white px-4 py-2 rounded-r-md hover:bg-yellow-600 transition-all duration-300">
                Pesquisar
            </button>
        </div>
    </div>

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
                <td class="px-6 py-4 whitespace-nowrap">{{ 'R$ ' . number_format((float)$product->price, 2, ',', '.') }}</td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <form action="{{ route('barcode.generate') }}">
                        @csrf
                        <input type="hidden" name="sku" value="{{ $product->sku }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="type" value="{{$product->type}}">
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
    @if ($currentPage > 1)
        <button wire:click="previousPage" class="mx-1 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            Anterior
        </button>
    @endif

    @for ($i = 1; $i <= $totalPages; $i++)
        <button wire:click="goToPage({{ $i }})"
            class="mx-1 px-3 py-1 {{ $i == $currentPage ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-gray-300">
            {{ $i }}
        </button>
    @endfor

    @if ($currentPage < $totalPages)
        <button wire:click="nextPage" class="mx-1 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            Próximo
        </button>
    @endif
</div>
</div>
