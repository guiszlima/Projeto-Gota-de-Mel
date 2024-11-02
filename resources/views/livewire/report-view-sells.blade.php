@php
$totalItems = count($items);
@endphp

<div class="p-6">

    <!-- Filtros e outros componentes... -->

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
                @foreach($items->groupBy('product_id') as $productId => $groupedItems)
                <!-- Linha principal do grupo com o ID do produto -->
                <tr class="bg-gray-200">
                    <td colspan="10" class="px-6 py-4 text-sm font-semibold text-gray-800 text-left">
                        Produto ID: {{ $productId }}
                    </td>
                </tr>

                @foreach($groupedItems as $item)
                <tr class="bg-white hover:bg-gray-50">

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->nome }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        R${{ number_format($item->preco, 2, ',', '.') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->estante }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->prateleira }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-gray-900">

                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ $item->pagamento === 'credit' ? 'crédito' : ($item->pagamento === 'debit' ? 'débito' : $item->pagamento) }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">

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