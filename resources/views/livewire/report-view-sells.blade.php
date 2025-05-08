@php
$totalItems = count($items);
@endphp

<div class="p-6" x-data="{ isOpen: false }">
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
            

            <!-- Input para Id -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Id</label>
                <input type="search" wire:model="searchId" placeholder="ID do produto"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">


            </div>
            <div>
                <label class=" block text-sm font-medium text-gray-700">Venda</label>
                <input type="search" wire:model="searchSellId" placeholder="ID da Venda"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">

            </div>
            <!-- Input para Preço -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Preço Total</label>
                <input type="search" wire:model="searchPrice" placeholder="Preço total" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Preço de Pagamentos</label>
                <input type="search" wire:model="searchPayment" placeholder="Preço do produto"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Preço de Troco</label>
                <input type="search" wire:model="searchTroco" placeholder="Preço do Troco"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:border-blue-300">

            </div>
            <div>
        <label class="block text-sm font-medium text-gray-700">Desconto</label>
        <input type="number" wire:model="searchDiscount" placeholder="Filtrar por desconto"
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
    <div class="mb-6 text-right">
    <button wire:click="downloadPDF"
    class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
    Baixar Vendas
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
                    
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Troco</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Parcelas</th>
                    <th scope="col" class="px-6 py-3 text-sm font-medium text-gray-900">Desconto</th>
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


                    
               

                     </td> 
                     <td class="px-2 py-1 whitespace-nowrap">
                     
    <!-- Botão para abrir o modal -->
    <button x-on:click="isOpen = true" wire:click="trocaProduct({{ $groupedItems }})"
    class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
    Troca de Produto
</button>

    
</td>
                </tr>

                @foreach($groupedItems as $item)
                <tr class="bg-white hover:bg-gray-50">

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $item->user_name }}</td>

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        R${{ number_format($item->preco, 2, ',', '.') }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ 'R$ ' . number_format($item->preco_total, 2, ',', '.')  }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ implode(', ', json_decode($item->produtos)) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-gray-900">
                        {{ $item->cancelado == 0 ? 'Aprovado' : 'Cancelado' }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{ $item->pagamento === 'credit' ? 'crédito' : ($item->pagamento === 'debit' ? 'débito' : $item->pagamento) }}
                    </td>
                   
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{$item->troco}}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{$item->parcelas}}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                        {{$item->desconto?? ""}}
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
 
 
     <!-- Modal -->
     <div x-show="isOpen" x-transition 
     x-data 
     x-on:close-modal.window="isOpen = false"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">

     <div class="bg-gray-100 p-8 rounded-xl shadow-md w-full max-w-lg sm:max-w-xl md:max-w-2xl lg:max-w-3xl">

        <h2 class="text-lg font-semibold mb-6 text-center">Itens a serem Trocados</h2>
        <button wire:click='fechaModal'  x-on:click="isOpen = false" class="mt-4 mb-2 px-4 py-2 bg-red-500 text-white rounded">
            Fechar
        </button>
        @if($itensTrocar && count($itensTrocar) > 0)
        
            <!-- Flex Container para os itens -->
            <div class="flex flex-wrap gap-6 justify-start h-96 overflow-y-auto">
            @foreach($itensTrocar as $item)
            
                <div class="flex-1 min-w-[200px] p-4 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-lg font-semibold text-gray-800 mb-2"><strong>Nome do Produto:</strong> {{ $item['nome_produto'] }}</p>
                    <p class="text-md text-gray-600 mb-2"><strong>Preço do Produto:</strong> R$ {{ number_format($item['preco_produto'], 2, ',', '.') }}</p>
                    <p class="text-md text-gray-600 mb-2"><strong>ID do Produto:</strong> {{ $item['id_produto'] }}</p>
                    <p class="text-md text-gray-600 mb-2"><strong>Quantidade:</strong> {{ $item['quantidade'] }}</p>
                    <p class="text-md text-gray-600 mb-2"><strong>Preço a ser trocado:</strong> {{ $item['cont'] *  $item['preco_produto']}}</p>
            
                    <div class="flex flex-col items-center">
                        <div class="flex items-center gap-2">
                            <button
                                class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center focus:outline-none"
                                wire:click="increment( {{ $item['preco_produto'] }}, {{ $item['id_produto'] }})">
                                +
                            </button>
                            <div>{{ $item['cont'] ?? '0' }}</div>
                            <button
                                class="bg-red-600 text-white rounded-full h-8 w-8 flex items-center justify-center focus:outline-none"
                                wire:click="decrement( {{ $item['preco_produto'] }}, {{ $item['id_produto'] }})">
                                -
                            </button>
                        </div>
                        <button
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline mt-2"
                            wire:click="realizarTroca(
                                {{ $item['venda_id'] }},
                                {{ $item['id_produto'] }},
                                {{ $item['cont'] ?? 0 }},
                                {{ $item['preco_produto'] }},
                                {{ $item['quantidade'] }},
                                '{{ $item['tipo'] ?? 'simple' }}',
                                {{ $item['parent_id'] ?? 'null' }}
                            )">
                        Trocar
                    </button>
                    </div>
                </div>
            @endforeach
            
            </div>

           
            <div class="flex justify-center mt-4">
                <button
                    class="bg-yellow-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline"
                    wire:click="imprimirNota">
                    Imprimir Nota
                </button>
            </div>
            <div class="flex justify-center mt-4">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline"
                    wire:click="venderTroca">
                    Realizar Troca
                </button>
            </div>
            
            
        @else
            <p class="text-gray-500">Carregando. . ..</p>
        @endif

        
      
    </div>
</div>


</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>


function maskFloat(e) {
    let value = e.target.value;
    value = value.replace(/\D/g, '');
    value = (value / 100).toFixed(2) + '';
    value = value.replace(".", ",");
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    e.target.value = value;
}


window.addEventListener('alreadyCancelled', function(event) {

Swal.fire({
    title: 'A venda não foi realizada',
    icon: 'warning',
    confirmButtonText: 'Entendido'
});

});

window.addEventListener('productTooOld', function(event) {


Swal.fire({
    title: 'A venda ja aconteceu há mais de 7 dias',
    icon: 'warning',
    confirmButtonText: 'Entendido'
});})


window.addEventListener('troca-nao-realizada', function(event) {


Swal.fire({
    title: 'A troca não foi realizada',
    icon: 'warning',
    confirmButtonText: 'Entendido'
});})

window.addEventListener('troca-realizada', function(event) {


Swal.fire({
    title: 'A troca foi realizada',
    icon: 'success',
    confirmButtonText: 'Entendido'
});})


document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('renderizar-pdf', (url) => {
    const pdfUrl = url.detail[0].url;
console.log('omg hii',pdfUrl);
    // Use print.js para abrir e imprimir o PDF
    printJS({
        printable: pdfUrl,
        type: 'pdf',
        showModal: true,
        });
    });
});
</script>
<script>


window.addEventListener('export-sales-pdf', (url) => {
        const pdfUrl = url.detail[0].url;
        console.log('omg hii',pdfUrl);
        printJS({
        printable: pdfUrl,
        type: 'pdf',
        showModal: true,
        style: `
            @media print {
                /* Ajustar o zoom para impressão */
                body {
                    zoom: 1.5; /* Ajuste o valor de zoom para aumentar o conteúdo */
                }

                /* Ajustar configurações de página */
                @page {
                    size: A4;
                    margin: 1in;
                }
            }
        `
    });


    })
    






</script>
</div>
</div>