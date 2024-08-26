<div class="mt-10">
    <h1 class="text-2xl font-bold text-center mb-10">Criar</h1>
    <div class="flex flex-col items-center w-full space-y-6">
        <div class="flex flex-row space-x-10">
            <x-dynamic-link text="Produto sem Variações" route="stock.create" currentRoute="{{$currentRoute}}" />
            <x-dynamic-link text="Produto com Variações" route="{{$currentRoute}}" currentRoute="{{$currentRoute}}" />
        </div>
    </div>

    <div class="flex flex-col items-center mt-10 bg-gray-100 py-8">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center mb-6">Criar Produto Variável</h2>

            @if ($mensagem)
            <div class="my-10">
                <span class="text-red-500 my-5 ">{{ $mensagem }}</span>
            </div>

            @endif
            <!-- Nome do Produto -->
            <div class="mb-4">
                <label for="product-name" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input required type="text" id="product-name" name="product_name" wire:model="nome_produto"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">

            </div>

            <!-- Selecione um Atributo -->
            <div class="mb-4">
                <label for="attribute-select" class="block text-sm font-medium text-gray-700">Selecione um
                    Atributo</label>
                <select id="attribute-select" name="attribute" wire:model="atributo_pai"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" selected>Escolha uma opção</option>
                    @foreach ($attributes as $attribute)
                    <option value="{{ $attribute['id_pai'] }}">{{ $attribute['nome_pai'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Imagem -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Imagem</label>
                <input type="file" id="image" name="image"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    accept="image/*">
            </div>
            <div class="mb-10">
                <label for="category" class="block text-sm font-medium text-gray-700">
                    Categoria <span class="text-red-500">*</span>
                </label>
                <select wire:model="categoryValue" id="category" name="category"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    required>
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Gerar Variações -->
            <div class="mb-6">
                <button id="generate-variations" wire:click='choose' type="button"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Gerar Variações
                </button>
            </div>

            <!-- Inserir Preço e Imagem -->
            <div class="form-group {{$escolhido && $atributo_pai !== null && !$mensagem ? 'block' : 'hidden'}}">
                <div class="flex flex-col space-y-4">
                    <div class="flex flex-row space-x-4">
                        <input name="all-price" id="all-price" placeholder="Preço de Todos"
                            class="block w-full py-2 px-3 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        <button id="insert-price"
                            class="fa fa-refresh p-2 bg-amber-200 text-white rounded-full hover:bg-amber-300 hover:text-black transition duration-300"
                            type="button">
                        </button>
                    </div>

                    <div class="flex flex-row space-x-4">
                        <input type="text" id="all-quantity" name="all-quantity"
                            class="block w-full py-2 px-3 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Quantidade de todos" required>
                        <button id="insert-quantity"
                            class="fa fa-refresh p-2 bg-amber-200 text-white rounded-full hover:bg-amber-300 hover:text-black transition duration-300"
                            type="button">
                        </button>
                    </div>


                </div>
            </div>
        </div>

        <!-- Formulário de Variações -->
        @if($escolhido && $categoryValue && $atributo_pai !== null && !$mensagem)
        <form id="myForm" action="{{route('stock.store.var-product')}}" method="POST" enctype="multipart/form-data"
            class="w-full max-w-md mt-6">
            @csrf
            <div id="variations-container" class="space-y-4 w-full bg-white p-6 rounded-lg shadow-md">
                @foreach ($attributes as $attribute)
                @if($attribute['id_pai'] == $atributo_pai)
                @foreach($attribute['atributos_filhos'] as $term)
                <div class="flex justify-between items-center mb-4">
                    <p>{{ $nome_produto . " " . $term['name'] }}</p>

                    <input type="text" name="preco[]" placeholder="Preço"
                        class="price-input block w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">

                    <input type="text" name="quantity[]" placeholder="Quantidade"
                        class="quantity-input block w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <input type="hidden" value="{{$term['id']}}" name="id_term[]">
                    <input type="hidden" class="all-sku" name="sku[]">
                    <input type="hidden" value="{{$term['name']}}" name="nome[]">
                </div>
                @endforeach
                @endif
                @endforeach
                <div class="flex justify-end mt-6">
                    <input type="hidden" value="{{$atributo_pai}}" name="attribute_dad[]">
                    <input type="hidden" name="nomeProduto">
                    <input type="text">
                    <input type="file" name="imagem" class="hidden" id="imagem">
                    <button type="button" value="{{$nome_produto}}" id="submitButton"
                        class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">
                        Salvar
                    </button>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
<script>
const insertPrice = document.getElementById('insert-price');
const insertQuantity = document.getElementById('insert-quantity')

function generateSku() {
    now = new Date();
    const year = now.getFullYear();
    const month = (now.getMonth() + 1).toString().padStart(2, '0');
    const day = now.getDate().toString().padStart(2, '0');
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const milliseconds = now.getMilliseconds().toString().padStart(3, '0');

    return `${year}${month}${day}${hours}${minutes}${seconds}${milliseconds}`;
}



insertPrice.addEventListener('click', function() {
    // Obter o valor do preço de todos
    const allPrice = document.getElementById('all-price').value;

    // Obter o valor do arquivo de todos


    // Selecionar todos os campos de preço e arquivo nas variações
    const priceInputs = document.querySelectorAll('.price-input');


    // Inserir o valor de todos os preços e arquivos nos inputs correspondentes
    priceInputs.forEach(input => {
        input.value = allPrice;
    });

})

insertQuantity.addEventListener('click', function() {
    const allQuantity = document.getElementById('all-quantity').value;
    const quantityInput = document.querySelectorAll('.quantity-input');
    quantityInput.forEach(input => {
        input.value = allQuantity;
    });
})

// Função para configurar o evento de clique no botão de submit
function setupSubmitButton() {
    const submitButton = document.getElementById('submitButton');
    if (submitButton) {
        const form = document.getElementById('myForm');
        submitButton.addEventListener('click', function() {
            const classSku = document.querySelectorAll('.all-sku'); // Corrigido para '.all-sku'
            const imageInput = document.getElementById('image');
            const imagemInput = document.getElementById('imagem');


            classSku.forEach((element, index) => {

                element.value = generateSku() + index;

            });

            // Chama a próxima iteração após 2 milissegundos

            // Após o loop
            if (imageInput.files.length > 0) {
                imagemInput.files = imageInput.files;
            }
            form.submit();




        });
    }
}

// Configurar o MutationObserver para detectar quando o submitButton é adicionado ao DOM
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length > 0) {
            setupSubmitButton();
        }
    });
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});

setupSubmitButton();
</script>