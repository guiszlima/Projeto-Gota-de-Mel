<div class="flex flex-col items-center h-screen bg-gray-100">
    <!-- Div de Aplicar Desconto (Sticky à esquerda) -->
  
    <!-- Botão de cancelar venda -->
    <div class="flex justify-center w-full">
        <a href="{{ route('products.sell') }}" class="w-10/12 mt-10 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-semibold rounded-lg text-base px-6 py-3 text-center mx-auto transition duration-300 transform hover:scale-105 shadow-xl">
            Cancelar venda
        </a>
    </div>

    <!-- Conteúdo centralizado -->
    <div class="flex justify-center w-full mt-10 relative">
    <!-- Div principal (centralizada) -->
    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-lg border border-gray-200">
        <div class="text-center mb-6">
            <h1 data-value={{ $total }} id="valor" class="text-3xl font-bold text-gray-800 mb-4">
                Total a pagar: R$ {{ $total }}
            </h1>

            <input wire:model="payment" required id="to-pay" autocomplete="off" type="text" placeholder="Insira valor para pagar" class="w-full p-3 border border-gray-300 rounded-lg mb-6 shadow-sm focus:outline-none focus:border-green-500 transition duration-200">

            <select wire:model="paymentmethod" id="payment-method" class="w-full p-3 border border-gray-300 rounded-lg mb-6 shadow-sm focus:outline-none focus:border-green-500 transition duration-200">
                <option value="debito" {{$sell['payment_method'] === 'Débito' ? 'selected':''}}>Débito</option>
                <option value="credit" {{$sell['payment_method'] === 'credit' ? 'selected':''}}>Crédito</option>
                <option value="pix" {{$sell['payment_method'] === 'Pix' ? 'selected':''}}>Pix</option>
                <option value="dinheiro" {{$sell['payment_method'] === 'Dinheiro' ? 'selected':''}}>Dinheiro</option>
                <option value="voucher" {{$sell['payment_method'] === 'voucher' ? 'selected':''}}>Voucher</option>
            </select>
        </div>

        <input wire:model="parcelas" type="text" placeholder="Em quantas vezes Deseja Parcelar?" name="Insira as Parcelas" id="parcelas" class="hidden w-full p-3 border border-gray-300 rounded-lg mb-6 shadow-sm focus:outline-none focus:border-green-500 transition duration-200">

        <div class="flex justify-center w-full mt-6">
            <button wire:click="selling({{$total}})" id="pagar" class="w-full text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-semibold rounded-lg text-base px-6 py-3 text-center mx-auto transition duration-300 transform hover:scale-105 shadow-xl">
                Pagar
            </button>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Itens do Carrinho</h2>
            <ul class="space-y-3">
                @foreach ($sell['cart'] as $item)
                <li class="border-b pb-3">
                    <p class="text-lg font-medium text-gray-800">{{ $item['name'] }}</p>
                    <p class="text-gray-600">Preço Unidade: R$ {{ number_format($item['value'], 2, ',', '.') }}</p>
                    <p class="text-gray-600">Preço Total: R$ {{ number_format($item['product_real_qtde'], 2, ',', '.') }}</p>
                    <p class="text-gray-600">Quantidade: {{ $item['quantidade'] }}</p>
                </li>
                @endforeach
                @if($troco)
                <p>Troco: R$ {{ number_format($troco, 2, ',', '.') }}</p>
                @endif
            </ul>
        </div>
    </div>

    <!-- Div de Aplicar Desconto (sobreposta no canto direito) -->
    <div class="absolute top-0 right-0 p-4 bg-white border border-gray-200 rounded-lg shadow-xl transform -translate-x-4 translate-y-4 transition-all duration-300 hover:shadow-2xl">
    <label for="desconto" class="block text-sm font-medium text-gray-700 mb-2">Desconto</label>
    <input
        wire:model="desconto"
        type="text"
        id="desconto"
        name="desconto"
        placeholder="Digite o desconto"
        class="w-32 p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
        
    >

    <button wire:click="applyDiscount()" class="w-full p-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md mt-3 hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 transform hover:scale-105">
        Aplicar Desconto
    </button>
</div>
</div>

    <!-- Botão de gerar nota -->
    <button wire:click="printNota" id="printNota" class="mt-4 w-full max-w-md text-white font-medium py-3 px-6 bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
        Gerar nota
    </button>

    <!-- Botão de finalizar compra -->
    <button wire:click="endPurchase" id="concluirCompra" class="mt-8 w-11/12 max-w-md text-white font-bold py-4 px-8 bg-gradient-to-r from-teal-500 via-teal-600 to-teal-700 rounded-xl hover:bg-gradient-to-br focus:outline-none focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-800 transition duration-300 transform hover:scale-105 shadow-2xl">
        FINALIZAR COMPRA
    </button>
</div>

<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Código JavaScript
    const toPay = document.getElementById('to-pay');
    const desconto = document.getElementById('desconto'); // Corrigido a sintaxe do "="
    toPay.addEventListener('input', maskFloat);
    desconto.addEventListener('input', maskFloat);

    function maskFloat(e) {
        let value = e.target.value;
        value = value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
        value = (value / 100).toFixed(2) + ''; // Converte para número e formata para 2 casas decimais
        value = value.replace(".", ","); // Substitui ponto por vírgula
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // Adiciona ponto como separador de milhar
        e.target.value = value; // Atualiza o valor do input
    }

    window.addEventListener('paymentTooHigh', function(event) {
        const value = document.getElementById('valor');
        const atualValue = value.getAttribute('data-value'); // Obtém o valor do atributo
        Swal.fire({
            title: 'Valor de Pagamento Muito Alto',
            html: `Por favor, insira um valor menor ou igual a: <span style="color: #e3342f; font-weight: bold;">R$ ${atualValue}</span> para prosseguir com o pagamento.`,
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });
    });

    window.addEventListener('noPayment', function(event) {
        Swal.fire({
            title: 'Não foi inserido um pagamento',
            html: `Por favor, insira um valor.`, 
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });
    });
    // Evento para quando não for inserido um desconto
window.addEventListener('noInsertDiscount', function() {
    Swal.fire({
        title: 'Desconto Não Inserido',
        text: 'Por favor, insira um valor de desconto válido.',
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });
});

// Evento para quando o desconto for muito alto
window.addEventListener('tooBigDiscount', function() {
    const value = document.getElementById('valor');
    const atualValue = value.getAttribute('data-value'); // Obtém o valor do atributo
    
    Swal.fire({
        title: 'Valor de Desconto Muito Alto',
        html: `O desconto não pode ser maior que o valor total da venda. Por favor, insira um valor menor ou igual a: <span style="color: #e3342f; font-weight: bold;">R$ ${atualValue}</span>`,
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });
});
</script>
