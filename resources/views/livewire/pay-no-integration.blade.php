<div>


<div class="flex justify-center w-full">
    {{-- Botão de cancelar venda --}}
    <a href="{{ route('products.sell') }}" class="w-10/12 mt-10 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-semibold rounded-lg text-base px-6 py-3 text-center mx-auto transition duration-300 transform hover:scale-105 shadow-xl">
        Cancelar venda
    </a>
</div>

    <div class="flex flex-col mt-10 items-center h-screen bg-gray-100">
        {{-- Container para o conteúdo centralizado --}}
        <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-lg border border-gray-200">
            <div class="text-center mb-6">
                {{-- Exibe o valor total calculado do carrinho --}}
                <h1 data-value={{ $total }} id="valor"
                    class="text-3xl font-bold text-gray-800 mb-4">
                    Total a pagar: R$ {{ $total }}
                </h1>

                {{-- Input para inserir o valor a ser pago --}}
                <input wire:model="payment" required id="to-pay" autocomplete="off" type="text"
                    placeholder="Insira valor para pagar"
                    class="w-full p-3 border border-gray-300 rounded-lg mb-6 shadow-sm focus:outline-none focus:border-green-500 transition duration-200">

                {{-- Selecionar forma de pagamento --}}
                <select wire:model="paymentmethod" id="payment-method"
                    class="w-full p-3 border border-gray-300 rounded-lg mb-6 shadow-sm focus:outline-none focus:border-green-500 transition duration-200">
                    <option value="debito" {{$sell['payment_method'] === 'Débito' ? 'selected':''}}>Débito</option>
                    <option value="credito" {{$sell['payment_method'] === 'Crédito' ? 'selected':''}}>Crédito</option>
                    <option value="pix" {{$sell['payment_method'] === 'Pix' ? 'selected':''}}>Pix</option>
                    <option value="dinheiro" {{$sell['payment_method'] === 'Dinheiro' ? 'selected':''}}>Dinheiro
                    </option>
                </select>
            </div>

            {{-- Input de parcelas --}}
            <input wire:model="parcelas" type="text" placeholder="Em quantas vezes Deseja Parcelar?"
                name="Insira as Parcelas" id="parcelas"
                class="hidden w-full p-3 border border-gray-300 rounded-lg mb-6 shadow-sm focus:outline-none focus:border-green-500 transition duration-200">

            {{-- Botão de pagar --}}
            <div class="flex justify-center w-full mt-6">
                <button wire:click="selling({{$total}})" id="pagar"
                    class="w-full text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-semibold rounded-lg text-base px-6 py-3 text-center mx-auto transition duration-300 transform hover:scale-105 shadow-xl">
                    Pagar
                </button>
            </div>

            {{-- Listagem dos itens do carrinho --}}
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Itens do Carrinho</h2>
                <ul  class="space-y-3">
                    @foreach ($sell['cart'] as $item)
                    <li  class="border-b pb-3">
                        <p class="text-lg font-medium text-gray-800">{{ $item['name'] }}</p>
                        <p class="text-gray-600">Preço Unidade: R$ {{ number_format($item['value'], 2, ',', '.') }}</p>
                        <p class="text-gray-600">Preço Total: R$
                            {{ number_format($item['product_real_qtde'], 2, ',', '.') }}</p>
                        <p class="text-gray-600">Quantidade: {{ $item['quantidade'] }}</p>
                    </li>
                    @endforeach
                    @if($troco)
    <p>Troco: R$ {{ number_format($troco, 2, ',', '.') }}</p>
@endif

                </ul>
            </div>
        </div>
        <button wire:click="printNota" id="printNota"
        class="mt-8 w-11/12 max-w-md text-white font-bold py-4 px-8 bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 rounded-xl hover:bg-gradient-to-br focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-800 transition duration-300 transform hover:scale-105 shadow-2xl">
       Gerar nota
</button>

        
        <button wire:click="endPurchase" id="concluirCompra"
            class=" mt-8 w-11/12 max-w-md text-white font-bold py-4 px-8 bg-gradient-to-r from-teal-500 via-teal-600 to-teal-700 rounded-xl hover:bg-gradient-to-br focus:outline-none focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-800 transition duration-300 transform hover:scale-105 shadow-2xl">
            FINALIZAR COMPRA
        </button>
    </div>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const toPay = document.getElementById('to-pay');
    toPay.addEventListener('input', maskFloat);

    function maskFloat(e) {
        let value = e.target.value;
        value = value.replace(/\D/g, '');
        value = (value / 100).toFixed(2) + '';
        value = value.replace(".", ",");
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        e.target.value = value;
    }
    window.addEventListener('paymentTooHigh', function(event) {
        const value = document.getElementById('valor');
        const atualValue = value.getAttribute('data-value'); // Obtém o valor do atributo
        Swal.fire({
            title: 'Valor de Pagamento Muito Alto',
            html: `Por favor, insira um valor menor ou igual a: <span style="color: #e3342f; font-weight: bold;">R$ ${atualValue}</span> para prosseguir com o pagamento.`, // Corrigido aqui
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });

    });

    window.addEventListener('noPayment', function(event) {


        Swal.fire({
            title: 'Não foi inserido um pagamento',
            html: `Por favor, insira um valor.`, // Corrigido aqui
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });

    });
    document.addEventListener('DOMContentLoaded', () => {
        const paymentMethod = document.getElementById('payment-method');
        const parcelas = document.getElementById('parcelas');

        // Adiciona um evento de 'change'
        paymentMethod.addEventListener('change', () => {
            if (paymentMethod.value === "credito") {
                parcelas.classList.remove('hidden');
                // Mostra o input de parcelas
            } else {
                parcelas.classList.add('hidden'); // Esconde o input de parcelas
                parcelas.value = "";
            }
        });
    });

    window.addEventListener('printNotaAlert', () => {

        Swal.fire({
            title: 'O valor total ainda não foi pago',
            html: `É apenas permitido imprimir a nota fiscal quando o valor total for igual a 0,00.`, 
            icon: 'warning',
            confirmButtonText: 'Ok'
        });


    });
    
    window.addEventListener('printNotaAlert', () => {

Swal.fire({
    title: 'O valor total ainda não foi pago',
    html: `É apenas permitido imprimir a nota fiscal quando o valor total for igual a 0,00.`, 
    icon: 'warning',
    confirmButtonText: 'Ok'
});

    });

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


window.addEventListener('endPurchaseAlert', () => {

    Swal.fire({
        title: 'O valor total ainda não foi pago',
        html: `Por favor, apenas conclua a venda quando o valor total for igual a 0,00.`, 
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });


});
window.addEventListener('hasTroco', function(event) {


    Swal.fire({
        title: 'Valor maior que o total e já há troco registrado. ',
        html: `Caso necessário favor refazer a compra`, // Corrigido aqui
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });

});



    </script>

</div>