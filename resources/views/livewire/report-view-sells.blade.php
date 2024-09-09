<div class="p-6">
    <!-- Filtros por tipo de produto: Variantes e Simples -->
    <div class="mb-4">
        <span class="text-lg font-semibold">Tipo de Produto</span>
        <div class="flex items-center space-x-4 mt-2">
            <!-- Radio para Variantes -->
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedType" name="product_type" value="Variante"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Variantes</span>
            </label>

            <!-- Radio para Simples -->
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedType" name="product_type" value="Simples"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Simples</span>
            </label>
        </div>
        <!-- Botão de limpar -->
        <button type="button" wire:click="clearSelection" class="mt-2 px-4 py-2 bg-gray-300 text-black rounded">
            Limpar Seleção
        </button>
    </div>


    <!-- Filtros por Nome, Id, Preço, Data Início, e Data Fim -->
    <div class="mb-6">
        <span class="text-lg font-semibold">Procurar por</span>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
            <!-- Input para Nome -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="search" wire:model="searchName" placeholder="Nome do produto"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Input para Id -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Id</label>
                <input type="search" wire:model="searchId" placeholder="ID do produto"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Input para Preço -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="search" wire:model="searchPrice" placeholder="Preço do produto"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Inputs para Data Início e Data Fim agrupados -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Período</label>
                <div class="flex space-x-2">
                    <input type="date" wire:model="searchStartDate"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <span class="self-center capitalize text-gray-600">até</span>
                    <input type="date" wire:model="searchEndDate"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                </div>
            </div>
        </div>
    </div>

    <!-- Botão que desencadeia o evento no Livewire -->
    <div class="mb-6 text-right">
        <button wire:click="applyFilters"
            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
            Aplicar Filtros
        </button>
    </div>

    <!-- Tabela de Produtos -->
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-lg">
        <table class="min-w-full table-auto text-center mb-10">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Id</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Nome</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Preço</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">CPF</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Tipo</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Data</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($items as $item)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->product_id }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->nome }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->preco }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->CPF }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->type }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-center">
            {{ $items->links() }}
        </div>

    </div>


</div>