@php
$totalItems = count($items);
@endphp

<div class="p-6">

    <!-- Filtros por tipo de produto: Variantes e Simples -->
    <div class="mb-4">
        <span class="text-lg font-semibold">Tipo de Produto</span>
        <div class="flex items-center space-x-4 mt-2">
            <!-- Radio para Variantes -->
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedPay" name="product_type" value="pix"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Pix</span>
            </label>

            <!-- Radio para Simples -->
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedPay" name="product_pay" value="dinheiro"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Dinheiro</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedPay" name="product_pay" value="debit"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Débito</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedPay" name="product_pay" value="credit"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Crédito</span>
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
            <div>
                <label class="block text-sm font-medium text-gray-700">CPF</label>
                <input type="search" wire:model="searchCPF" placeholder="Buscar por CPF"
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
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Localização</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Input para Estoque -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estoque</label>
                        <input type="search" wire:model="estoque" placeholder="Estoque"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <!-- Input para Estante -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estante</label>
                        <input type="search" wire:model="estante" placeholder="Estante"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <!-- Input para Prateleira -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prateleira</label>
                        <input type="search" wire:model="prateleira" placeholder="Prateleira"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>
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
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Estoque</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Estante</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Prateleira</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Pagamento</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">CPF</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Quantidade</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Data</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <div class="mb-4 bg-gray-100 p-4 rounded-lg shadow-md">
                    <p class="text-lg font-semibold text-gray-800">Total de itens:
                        <span class="text-blue-600">{{ $totalItems }}</span>
                    </p>
                    <p class="text-lg font-semibold text-gray-800">Soma de Preços:
                        <span class="text-green-600">R$ {{ number_format($soma, 2, ',', '.') }}</span>
                    </p>
                </div>
                @foreach($items as $item)

                <tr class="bg-white hover:bg-gray-50">
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->product_id }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->nome }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->preco }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->estoque }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->estante }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->prateleira }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-gray-900">
                        {{ $item->pagamento === 'credit' 
                        ? 'crédito' 
                        : ($item->pagamento === 'debit' 
                            ? 'débito' 
                            : $item->pagamento) 
                    }}</td>

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->CPF }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->quantidade }}</td>
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