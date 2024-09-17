<div class="flex flex-row w-full">

    <div class="flex my-8 w-7/12 justify-start flex-col">




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
                <button id="procura-produtos"
                    class="float-start text-gray-900 {{$formType? 'block':'hidden'}} hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ml-2 me-2 mb-4 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                    type="submit">
                    Procurar
                </button>





            </div>
        </form>

        @if (!empty($data) && !empty($data['products']) )
        <div class="w-full my-4">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($data['products'] as $product)
                @if (isset($data['quantidade'][$product->id]) && $formType)

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
                            @if ($product->stock_quantity!=0)


                            <button
                                class="bg-blue-600 text-white rounded mt-2 px-6 py-2 text-xs font-medium uppercase transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 active:bg-blue-800"
                                wire:click="addToCart( '{{$product->name}}','{{$product->price}}','{{$product->id}}' )">
                                Adicionar
                            </button>
                            @else
                            <p>FORA DE ESTOQUE</p>
                            @endif
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
    <div class="flex flex-col my-8 w-5/12  ">
        <button
            class="relative h-[35px] w-full mr-7 mb-2 items-center justify-center overflow-hidden bg-gray-800 text-white shadow-2xl transition-all before:absolute before:h-0 before:w-0 before:rounded-full before:bg-orange-600 before:duration-500 before:ease-out hover:shadow-orange-600 hover:before:h-56 hover:before:w-full rounded-full {{ $cart ? 'flex' : 'hidden' }}"
            type="button" id="open_modal">
            <span class="relative z-10">Finalizar Compra</span>
        </button>
        @foreach ($cart as $item)
        <div class="border border-slate-500 p-4 mr-5 mb-4 rounded-lg flex justify-between items-center">
            <div class="text-center">
                <p class="text-lg font-bold">{{$item['name']}}</p>
                <p class="text-sm ">{{$item['value'] * $item['quantidade']}}</p>
                <p class="text-sm "> {{$item['quantidade']}}</p>
            </div>

            <div class="flex  flex-col items-center">

                <button
                    class="bg-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center mr-2 focus:outline-none"
                    wire:click="increment({{$item['id']}})">
                    +
                </button>
                <button
                    class="bg-red-600 text-white rounded-full h-8 w-8 flex items-center justify-center focus:outline-none"
                    wire:click="decrement({{$item['id']}},'{{$item['quantidade']}}')">
                    -
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif


    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="relative bg-white p-6 rounded-lg w-max">
            <button id="close_modal" class="absolute top-0 right-0 mt-2 mr-2 bg-red-600 text-white rounded px-4 py-2">
                Fechar
            </button>
            <form class="flex flex-col px-20 items-center" method="post" action="{{route('products.payment')}}">
                @csrf
                <x-payment-options></x-payment-options>
                <input type="hidden" name="cart" value="{{json_encode($cart)}}">
                <button
                    class="select-none rounded-lg bg-green-500 mt-2 py-3 px-6 w-1/2 text-center align-middle  font-sans text-xs font-bold uppercase text-white shadow-md shadow-green-500/20 transition-all hover:shadow-lg hover:shadow-green-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none "
                    type="submit">
                    realizar compra
                </button>
            </form>
        </div>
    </div>



</div>



<script>
let previousValue = '';
let timeout;
let formtype = false
const form = document.getElementById('procura-produtos')
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


            if (currentValue.length === 12 || currentValue.length === 13 || currentValue.length === 8) {
                console.log("Código de barras detectado: ", currentValue);
                form.click();
                inputField.value = '';
            }
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

// parte do modal
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal');
    const btnClose = document.getElementById('close_modal')

    function toggleModal() {
        modal.classList.toggle('hidden');
    }

    // Observa mudanças no DOM para detectar quando o botão é adicionado
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList') {
                const btnOpen = document.getElementById('open_modal');
                if (btnOpen && modal && btnClose) {
                    btnOpen.addEventListener('click', toggleModal);
                    btnClose.addEventListener('click', toggleModal);



                    observer.disconnect(); // Desconecta o observer após encontrar o botão
                }
            }
        });
    });

    // Configuração do observer
    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });

    // Event listener para fechar o modal com a tecla Escape
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            toggleModal();
        }
    });
});
</script>

</div>