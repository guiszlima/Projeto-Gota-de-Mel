<div class="mt-10">
    <h1 class="text-2xl font-bold m-10">Criar</h1>
    <div class="flex flex-col justify-around w-full">

        <div class="flex flex-row justify-around w-1/3 ">

            <x-dynamic-link text="Produto sem Variações" route="stock.create" currentRoute="{{$currentRoute}}" />
            <x-dynamic-link text="Produto com Variações" route="{{$currentRoute}}" currentRoute="{{$currentRoute}}" />
        </div>
    </div>
    <div class="flex justify-center flex-col items-center mt-10 bg-gray-100">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center mb-6">Criar Produto Variável</h2>

            <!-- Nome do Produto -->
            <div class="mb-4">
                <label for="product-name" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input required type="text" id="product-name" name="product_name" wire:model="nome_produto"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                @if ($mensagem)
                <span class="text-red-500 text-sm">{{ $mensagem }}</span>
                @endif
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

            <!-- Gerar Variações -->
            <div class="mb-6">
                <button id="generate-variations" wire:click='choose' type="button"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Gerar Variações
                </button>
            </div>

            <!-- Inserir Preço e Imagem -->

            <div class="form-group {{$escolhido && $atributo_pai !== null && !$mensagem? 'block':'hidden'}}">
                <label for="all-price">Preço de todos</label>
                <input type="text" class="form-control" name="all-price" id="all-price"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    placeholder="Preço de Todos">
                <div class="form-group">
                    <label for="all-file">Imagem de Todos</label>
                    <input type="file" name="all-file" id="all-file">
                </div>
                <button id="insert-values"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Inserir
                </button>
            </div>

        </div>

        <!-- Formulário de Variações -->
        @if($escolhido && $atributo_pai !== null && !$mensagem)
        <form id="myForm" action="{{route('stock.store.var-product')}}" method="POST" enctype="multipart/form-data"
            class="w-full mt-6">
            @csrf
            <div id="variations-container" class="space-y-4 w-full bg-white p-6 rounded-lg shadow-md">
                @foreach ($attributes as $attribute)
                @if($attribute['id_pai'] == $atributo_pai)
                @foreach($attribute['atributos_filhos'] as $term)

                <div class="flex justify-between items-center mb-4">
                    <p>{{ $nome_produto . " " . $term['name'] }}</p>
                    <input type="file" name="file[]" class="file-input">
                    <input type="text" name="preco[]" placeholder="Preço"
                        class="price-input ml-4 block w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <input type="hidden" value="{{$term['id']}}" name="id_term[]">
                    <input type="hidden" class="hasImage" name="has_image[{{$term['id']}}]">
                    <input type="hidden" value="{{$atributo_pai}}" name="attribute_dad[]">
                    <input type="hidden" value="{{$term['name']}}" name="'nome[]">
                </div>

                @endforeach
                @endif
                @endforeach
                <div class="flex justify-end mt-6">
                    <button type="button" id="submitButton"
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
const insertValues = document.getElementById('insert-values');

insertValues.addEventListener('click', function() {
    // Obter o valor do preço de todos
    const allPrice = document.getElementById('all-price').value;

    // Obter o valor do arquivo de todos
    const allFile = document.getElementById('all-file').files[0];

    // Selecionar todos os campos de preço e arquivo nas variações
    const priceInputs = document.querySelectorAll('.price-input');
    const fileInputs = document.querySelectorAll('.file-input');

    // Inserir o valor de todos os preços e arquivos nos inputs correspondentes
    priceInputs.forEach(input => {
        input.value = allPrice;
    });

    fileInputs.forEach(input => {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(allFile);
        input.files = dataTransfer.files;
    });
});

// Função para configurar o evento de clique no botão de submit
function setupSubmitButton() {
    const submitButton = document.getElementById('submitButton');
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            const fileInputs = document.querySelectorAll('.file-input');
            const hasImageInputs = document.querySelectorAll('.hasImage');
            const form = document.getElementById('myForm');

            fileInputs.forEach((input, index) => {
                // Verifica se o input de arquivo possui arquivos
                const temImagem = input.files.length > 0;
                // Define o valor do input oculto baseado na presença de imagem
                hasImageInputs[index].value = temImagem ? 'true' : 'false';
            });

            // Envia o formulário manualmente
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

// Começa a observar o DOM para alterações
observer.observe(document.body, {
    childList: true,
    subtree: true
});

// Configura o botão de submit se já estiver no DOM
setupSubmitButton();
</script>