<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gerenciamento de Estoque</h1>

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


    <form action="{{route('stock.create')}}" class="my-7 flex flex-row gap-4">
        <button
            class="relative px-8 py-2 rounded-md bg-white isolation-auto z-10 border-2 text-gray-700 border-yellow-300 before:absolute before:w-full before:transition-all before:duration-700 before:hover:w-full before:-left-full before:hover:left-0 before:rounded-full before:bg-yellow-300 before:-z-10 before:aspect-square before:hover:scale-150 overflow-hidden before:hover:duration-700">
            <span class="font-bold">Criar Produto</span>
        </button>

        <div class="flex-grow"></div>

        <a href="{{ route('report.products') }}" class="flex justify-end bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border
                border-blue-700 rounded">
            Relatório
        </a>
    </form>

    @if (session('success'))
    <div class="border border-green-500 text-green-700 bg-green-100 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Tabela de produtos -->
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Preço</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($products as $product)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $product->price }}</td>
                <td class="px-6 py-4 whitespace-nowrap flex space-x-4 justify-end">
                    <a href="{{ route('stock.show', $product->id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded">
                        Editar
                    </a>

                    <form action="{{ route('stock.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja deletar este item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded ml-10">
                            Deletar
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
<script>
const limpar = document.getElementById('limpar');
const searchTerm = document.getElementById('searchTerm');
limpar.addEventListener('click', () => {
    searchTerm.value = ''


})
</script>