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
                    <option value="{{ json_encode($attributes[$loop->index]) }}">{{ $attribute['nome_pai'] }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Descrição do Produto -->
            <div class="mb-4">
                <label for="product-description" class="block text-sm font-medium text-gray-700">Descrição do
                    Produto</label>
                <textarea id="product-description"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    rows="4"></textarea>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="height" class="block text-gray-700 font-bold mb-2">
                                Altura (cm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="height" name="height"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Digite a altura do produto" required>
                        </div>

                        <div>
                            <label for="width" class="block text-gray-700 font-bold mb-2">
                                Largura (cm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="width"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Digite a largura do produto" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="depth" class="block text-gray-700 font-bold mb-2">
                                Profundidade (cm)
                            </label>
                            <input type="number" id="depth"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Digite a profundidade do produto">
                        </div>

                        <div>
                            <label for="weight" class="block text-gray-700 font-bold mb-2">
                                Peso (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="weight" step="0.01"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Digite o peso do produto" required>
                        </div>
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
                @if($attribute['id_pai'] == $atributo_pai['id_pai'])
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

                    <input type="hidden" value="{{$atributo_pai['id_pai']}}" name="attribute_dad[]">
                    <input type="hidden" name="nomeProduto" value="{{$nome_produto}}">
                    <input type="hidden" id="send-depth" name="depth">
                    <input type="hidden" name="nameAttribute" value="{{$atributo_pai['nome_pai']}}">
                    <input type="hidden" name="height" id="send-height">
                    <input type="hidden" name="width" id="send-width">
                    <input type="hidden" name="weight" id="send-weight">
                    <input type="hidden" name="category" value="{{$categoryValue}}">
                    <input type="file" name="imagem" class="hidden" id="imagem">
                    <input type="hidden" name="description" id="send-description">
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
const insertQuantity = document.getElementById('insert-quantity');

function generateSku() {
    const now = new Date();
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
    const allPrice = document.getElementById('all-price').value;
    const priceInputs = document.querySelectorAll('.price-input');
    priceInputs.forEach(input => {
        input.value = allPrice;
    });
});

insertQuantity.addEventListener('click', function() {
    const allQuantity = document.getElementById('all-quantity').value;
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.value = allQuantity;
    });
});

function setupSubmitButton() {

    const submitButton = document.getElementById('submitButton');
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            const description = document.getElementById('product-description').value;

            const depth = document.getElementById('depth').value;
            const height = document.getElementById('height').value;
            const width = document.getElementById('width').value;
            const weight = document.getElementById('weight').value;

            document.getElementById('send-description').value = description;

            document.getElementById('send-depth').value = depth;
            document.getElementById('send-height').value = height;
            document.getElementById('send-width').value = width;
            document.getElementById('send-weight').value = weight;

            const classSku = document.querySelectorAll('.all-sku');
            const imageInput = document.getElementById('image');
            const imagemInput = document.getElementById('imagem');

            classSku.forEach((element, index) => {
                element.value = generateSku() + index;
            });

            if (imageInput.files.length > 0) {
                imagemInput.files = imageInput.files;
            }

            const form = document.getElementById('myForm');
            form.submit();
        });
    }



}

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