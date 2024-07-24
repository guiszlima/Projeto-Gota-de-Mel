<div class="my-8">
    <form class="flex justify-start items-start flex-row " wire:submit.prevent="fetchProducts">
        <input id="codigoBarras" required autocomplete="off" class="w-2/5 mx-3 mb-3 h-10 rounded text-center  "  name="products" type="text" wire:model="searchTerm" placeholder="Procurar Produtos">
        <button class="  text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-4 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" type="submit">Procurar</button>
    </form>
    
    @if (!empty($data) && !empty($data['products']))
<div class=" flex justify-center my-4 mx-auto flex-col w-5/6">


            <form class="flex self-center flex-col w-100%" action="{{route('products.make-sell')}}" method="POST">
                @csrf
                <x-payment-options></x-payment-options>
                <button class="relative flex h-[35px] w-40 my-7  self-center items-center justify-center overflow-hidden bg-gray-800 text-white shadow-2xl transition-all before:absolute before:h-0 before:w-0 before:rounded-full before:bg-orange-600 before:duration-500 before:ease-out hover:shadow-orange-600 hover:before:h-56 hover:before:w-56 rounded-full" type="submit" id="paymentButton">
      <span class="relative z-10">Definir Pagamento</span>
    </button>
                
                <div class="flex flex-row space-x-4 flex-wrap">   
                @foreach ($data['products'] as $product)
                        @if (isset($data['quantidade'][$product->id]))
                            


                        
                        <div class="bg-white w-max p-3 rounded-lg">
                                 <div class="my-2 text-center">
                                 {{ $product->name }} 
                                </div>
                            <div class="my-2 text-center">
                                Preço: {{ $currentPrice = $product->price * $data['quantidade'][$product->id] }}
                            </div> 

                            <div class="text-center">
                                Quantidade:
     
                               
                               
                               
                               {{ $data['quantidade'][$product->id] }}
                               <div class=" flex flex-col">
                               <button class="bg-green-700 mx-auto my-2 text-white rounded-full font-extrabold w-[80px] hover:bg-green-800" type="button" wire:click="increment({{ $product->id }})">+</button>
                               
                               <button class="bg-red-700 text-white w-[80px] mx-auto	 rounded-full font-extrabold hover:bg-red-800" type="button" wire:click="decrement({{ $product->id }})">-</button>
                                
                                </div>
                            </div>       
                                <input type="hidden" name="produtos[{{ $product->id }}][name]" value="{{ $product->name }}">
                                <input type="hidden" name="produtos[{{ $product->id }}][price]" value='{{$currentPrice}}'>
                                <input type="hidden" name="produtos[{{ $product->id }}][quantity]" value="{{ $data['quantidade'][$product->id] }}">
  

                            </div>

                        @endif
                    @endforeach
                    </div>
</div>
                

            </form>
        </div>
    @endif
    <script>
        let previousValue = '';
        let timeout;
        const inputField = document.getElementById('codigoBarras');


        inputField.addEventListener('keydown',()=>{

        })
        inputField.addEventListener('input', () => {
            const currentValue = inputField.value;
            clearTimeout(timeout);

            // Verifica se há diferença no valor atual e o anterior
            const insertedChars = currentValue.substring(previousValue.length);

            // Define um tempo limite para detectar a digitação gradual
            timeout = setTimeout(() => {
                if (insertedChars.length > 1) {
                    console.log(`Caracteres inseridos de uma vez: ${insertedChars}`);
                } else if (insertedChars.length === 1) {
                    // Manter o valor original para identificar a digitação gradual
                    inputField.value = '';
                    console.log('Caracteres digitados um por um');
                } else {
                    console.log('Nenhuma alteração detectada');
                }
                // Atualiza o valor anterior para a próxima comparação
                previousValue = currentValue;
            }, 50); // Ajuste o tempo conforme necessário
        });
        inputField.addEventListener('keydown', (event) => {
            // Se a tecla estiver pressionada por mais de 10ms
            keyDownTimeout = setTimeout(() => {
                inputField.value = ''; // Apaga o texto do input
                console.log('Texto apagado devido à tecla mantida');
            }, 10); // 10 milissegundos
        });

        inputField.addEventListener('keyup', (event) => {
            clearTimeout(keyDownTimeout); // Limpa o temporizador se a tecla for liberada antes dos 10ms
        });
    </script>

</div>
