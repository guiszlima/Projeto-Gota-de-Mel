@php
// Inicializa o valor total como 0
$total = 0;

// Itera sobre o array 'cart' dentro de 'sell' e soma os valores dos produtos
foreach (json_decode($sell['cart'], true) as $item) {
$total += $item['real_qtde'];
}
@endphp

<div> {{-- Botão de cancelar venda --}}

    <div class="flex justify-center w-full">
        <button type="button"
            class="w-10/12 mt-10 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-base px-6 py-3 text-center mx-auto transition duration-300 transform hover:scale-105 shadow-lg">
            Cancelar venda
        </button>
    </div>

    <div class="flex flex-col mt-10 items-center h-screen bg-gray-100">
        {{-- Container para o conteúdo centralizado --}}

        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <div class="text-center">
                {{-- Exibe o valor total calculado do carrinho --}}
                <h1 class="text-2xl font-semibold mb-4">Total a pagar: R$ {{ number_format($total, 2, ',', '.') }}</h1>

                {{-- Input para inserir o valor a ser pago --}}
                <input id="to-pay" type="text" placeholder="Insira valor para pagar"
                    class="w-full p-2 border border-gray-300 rounded-lg mb-6">

                {{-- Selecionar forma de pagamento --}}
                <select id="payment-method" class="w-full p-2 border border-gray-300 rounded-lg mb-6">
                    <option value="debito" {{$sell['payment_method'] === 'debito'? 'selected':''}}>Débito</option>
                    <option value="credito" {{$sell['payment_method'] === 'credito'? 'selected':''}}>Crédito</option>
                    <option value="pix" {{$sell['payment_method'] === 'pix'? 'selected':''}}>Pix</option>
                    <option value="dinheiro" {{$sell['payment_method'] === 'dinheiro'? 'selected':''}}>Dinheiro</option>
                </select>
            </div>

            {{-- Listagem dos itens no canto --}}
            <div class="mt-4">
                <h2 class="text-xl font-semibold mb-2">Itens do Carrinho</h2>
                <ul class="space-y-2">
                    @foreach (json_decode($sell['cart'], true) as $item)
                    <li class="border-b pb-2">
                        <p class="text-lg font-medium">{{ $item['name'] }}</p>
                        <p>Preço Unidade: R$ {{ number_format($item['value'], 2, ',', '.') }}</p>
                        <p>Preço Total: R$ {{ number_format($item['real_qtde'], 2, ',', '.') }}</p>
                        <p>Quantidade: {{ $item['quantidade'] }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

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
    </script>
</div>