<div class="flex flex-row w-full">
    <div class="flex my-8 w-7/12 justify-start flex-col">
        {{--<x-payment-options></x-payment-options>
         <button
            class="relative flex h-[35px] w-40 my-7 self-center items-center justify-center overflow-hidden bg-gray-800 text-white shadow-2xl transition-all before:absolute before:h-0 before:w-0 before:rounded-full before:bg-orange-600 before:duration-500 before:ease-out hover:shadow-orange-600 hover:before:h-56 hover:before:w-56 rounded-full"
            type="submit" id="paymentButton">
            <span class="relative z-10">Definir Pagamento</span>
        </button>--}}



        <form class="flex justify-start w-4/12 flex-col h-max" wire:submit.prevent="fetchProducts">
            <div class="relative w-11/12">
                <input id="codigoBarras" required autocomplete="off"
                    class="w-[50vw] mx-3 right-0 mb-3 h-10 rounded text-center pl-10" name="products" type="text"
                    wire:model="searchTerm" placeholder="Procurar Produtos" autofocus>

                <button type="button" id="change-form"
                    class="fa fa-search {{$formType ?'bg-pink-500':'bg-slate-800'}} absolute left-4 top-[20px] transform -translate-y-1/2 ml-1 w-9 h-8 rounded-full text-white"
                    wire:click="changeFormtype">
                    <!-- Ícone ou qualquer conteúdo -->
                </button>
                <button
                    class="float-start text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ml-2 me-2 mb-4 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                    type="submit">Procurar</button>
            </div>
        </form>

        @if (!empty($data) && !empty($data['products']))
        <div class="w-full my-4">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($data['products'] as $product)
                @if (isset($data['quantidade'][$product->id]))

                <div class="flex flex-col bg-white h-[200px] p-4 rounded-lg shadow-md">
                    <!-- Nome do produto -->
                    <div class="text-center font-bold mb-2">
                        {{ $product->name  }}

                    </div>
                    <!-- Preço e botão Adicionar -->
                    <div class="flex flex-col h-full justify-end">
                        <!-- Botão Adicionar -->
                        <div class="flex justify-end h-full flex-col text-center">
                            Preço: R${{$product->price  }}
                            <button
                                class="bg-blue-600 text-white rounded mt-2 px-6 py-2 text-xs font-medium uppercase transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 active:bg-blue-800"
                                wire:click="addToCart( '{{$product->name}}','{{$product->price}}','{{$product->id}}' )">
                                Adicionar
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>


    @if($cart)
    <div class="flex flex-col my-8 w-5/12 bg-black rounded-l-lg text-white">
        @foreach ($cart as $item)
        <div class="border border-white p-4 mb-4 rounded-lg flex justify-between items-center">
            <div class="text-center">
                <p class="text-lg font-bold">{{$item['name']}}</p>
                <p class="text-sm text-gray-300">{{$item['value']}}</p>
                <p class="text-sm text-gray-300"> {{$item['quantidade']}}</p>
            </div>
            <div class="flex items-center">

                <button
                    class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-2 focus:outline-none"
                    wire:click="increment">
                    +
                </button>
                <button
                    class="bg-red-600 text-white rounded-full h-8 w-8 flex items-center justify-center focus:outline-none"
                    wire:click="decrement">
                    -
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
<script>
let previousValue = '';
let timeout;
let formtype = false
const inputField = document.getElementById('codigoBarras');
const changeForm = document.getElementById('change-form')

changeForm.addEventListener('click', () => {
    if (formtype === false) {

        formtype = true

        inputField.value = '';
    } else {
        inputField.value = '';
        formtype = false
    }
})

inputField.addEventListener('keydown', () => {

})
inputField.addEventListener('input', () => {
    if (formtype === false) {
        const currentValue = inputField.value;
        clearTimeout(timeout);

        // Verifica se há diferença no valor atual e o anterior
        const insertedChars = currentValue.substring(previousValue.length);

        // Define um tempo limite para detectar a digitação gradual
        timeout = setTimeout(() => {
            if (insertedChars.length > 1) {

            } else if (insertedChars.length === 1) {
                // Manter o valor original para identificar a digitação gradual
                inputField.value = '';

            } else {

            }
            // Atualiza o valor anterior para a próxima comparação
            previousValue = currentValue;
        }, 50); // Ajuste o tempo conforme necessário
    }
});
inputField.addEventListener('keydown', (event) => {
    if (formtype === false) {
        // Se a tecla estiver pressionada por mais de 10ms
        keyDownTimeout = setTimeout(() => {
            inputField.value = ''; // Apaga o texto do input

        }, 10); // 10 milissegundos
    }
});

inputField.addEventListener('keyup', (event) => {
    if (formtype === false) {
        clearTimeout(keyDownTimeout); // Limpa o temporizador se a tecla for liberada antes dos 10ms
    }
});
</script>

</div>