@php
$totalItems = count($items);
@endphp

<div class="p-6">

    <!-- Filtros e outros componentes... -->
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

        <span class="text-lg font-semibold">Aprovado</span>
        <div class="flex items-center space-x-4 mt-2">
            <!-- Radio para Variantes -->
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedStatus" name="product_status" value="0"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Aprovado</span>
            </label>

            <!-- Radio para Simples -->
            <label class="flex items-center space-x-2">
                <input type="radio" wire:model="selectedStatus" name="product_status" value="1"
                    class="form-radio h-5 w-5 text-blue-600">
                <span>Cancelado</span>
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
                <input type="search" wire:model="searchName" placeholder="Buscar Nome "
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
            <div>
                <label class="block text-sm font-medium text-gray-700">Venda</label>
                <input type="search" wire:model="searchSellId" placeholder="ID da Venda"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">

            </div>
            <!-- Input para Preço -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Preço Total</label>
                <input type="search" wire:model="searchPrice" placeholder="Preço do produto"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Preço de Pagamentos</label>
                <input type="search" wire:model="searchPayment" placeholder="Preço do produto"
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

    <!-- Exibição do total de itens e soma de preços -->
    <div class="mb-4 bg-gray-100 p-4 rounded-lg shadow-md">
        <p class="text-lg font-semibold text-gray-800">Total de itens: <span
                class="text-blue-600">{{ $totalItems }}</span></p>
        <p class="text-lg font-semibold text-gray-800">Soma de Preços: <span class="text-green-600">R$
                {{ number_format($soma, 2, ',', '.') }}</span></p>
    </div>

    <!-- Tabela de Produtos -->
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-lg">
        <table class="min-w-full table-auto text-center mb-10">
            <thead class="bg-gray-100 border-b">
                <tr>

                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Nome</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Pagamentos</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Preço</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">ID dos Produtos</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Status</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Pagamento</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">CPF</th>

                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Data</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($items->groupBy('id') as $productId => $groupedItems)
                <!-- Linha principal do grupo com o ID do produto -->
                <tr class="bg-gray-200">
                    <td colspan="10" class="px-6 py-4 text-sm font-semibold text-gray-800 text-left">
                        Venda ID: {{ $productId }}
                    </td>
                </tr>

                @foreach($groupedItems as $item)
                <tr class="bg-white hover:bg-gray-50">

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->user_name }}</td>

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        R${{ number_format($item->preco, 2, ',', '.') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->preco_total }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ implode(', ', json_decode($item->produtos)) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-gray-900">
                        {{ $item->cancelado == 0 ? 'Aprovado' : 'Cancelado' }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ $item->pagamento === 'credit' ? 'crédito' : ($item->pagamento === 'debit' ? 'débito' : $item->pagamento) }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{$item->user_cpf}}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                    </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-center">
            {{ $items->links() }}
        </div>
    </div>
</div>