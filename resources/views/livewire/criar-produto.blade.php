<div>
<main class="flex flex-col items-center p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Criar Produto</h1>

    <x-warning :warn="session('warn')" />

    <div class="w-full max-w-lg bg-white p-6 rounded-xl shadow-lg">
       
       

            @isset ($mensagem)
            <div class="my-4 p-3 bg-red-100 border border-red-400 text-red-600 rounded-md">
                {{ $mensagem }}
            </div>
            @endisset

            <!-- Nome do Produto -->
            <div class="mb-5">
                <label for="product-name" class="block text-sm font-semibold text-gray-700">Nome do Produto</label>
                <input required type="text" id="product-name" name="product_name" placeholder="Digite o nome do produto..." wire:model="nomeProduto"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
            </div>

            <!-- Marca do Produto -->
            <div class="relative mb-5">
                <label for="brandInput" class="block text-sm font-semibold text-gray-700">Marca</label>
                <input type="text" id="brandInput" wire:model="brand" placeholder="Digite uma marca..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
                    oninput="filterBrands()"
                    autocomplete="off">
                <ul id="brandList"
                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 hidden max-h-48 overflow-y-auto shadow-lg transition-all duration-200">
                    <!-- Opções do select via JavaScript -->
                </ul>
            </div>

            <!-- Descrição do Produto -->
            <div class="mb-4">
                <label for="product-description" class="block text-sm font-medium text-gray-700">Descrição do Produto</label>
                <textarea id="product-description" wire:model="description" name="product_description"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
                    rows="4"></textarea>
            </div>

            <!-- Categoria -->
            <div class="mb-10">
                <label for="category" class="block text-sm font-medium text-gray-700">
                    Categoria <span class="text-red-500">*</span>
                </label>
                <select wire:model="categorySelected" id="category" name="category"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    required>
                    <option value="">Selecione uma categoria</option>
                    @if(is_array($categories) || is_object($categories))
                        @foreach($categories as $category)
                            <option value='@json(["id" => $category["id"], "name" => $category["name"]])'>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
    <!-- Checkbox -->
    <div>
    <!-- Botão para mostrar/ocultar a lista de cores -->
   

    <div class="relative mb-5">
    <button type='button' wire:click="toggleCores" class="px-4 py-2 bg-blue-500 text-white rounded-md">
        {{ $mostrarCores ? 'Ocultar Cores' : 'Mostrar Cores' }}
    </button>

    <!-- Lista de cores (só aparece se $mostrarCores for true) -->
    @if ($mostrarCores)
        <div class="mt-4 p-4 border rounded bg-gray-100">
            <h3 class="font-bold mb-2">Cores Disponíveis:</h3>

            <!-- Grid de duas colunas -->
            <div class="grid grid-cols-2 gap-2">
                @foreach ($cores as $cor)
                    <label class="flex items-center space-x-2 px-4 py-2 cursor-pointer bg-white border rounded-md shadow-sm hover:bg-gray-200">
                        <input type="checkbox" wire:model="coresSelecionadas.14" 
                               value='{{$cor["name"]}}' 
                               class="form-checkbox h-5 w-5 text-blue-500">
                        <span class="text-gray-700">{{ $cor['name'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif
</div>


</div>
<div class="relative mb-5">
    <label for="attributesInput" class="block text-sm font-semibold text-gray-700">Atributos</label>
    <div id="attributesInput" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
        @foreach($attr as $attributes)
            @if($attributes['name'] !== 'Cor')
                <label class="block px-4 py-2 cursor-pointer hover:bg-gray-200">
                    <input type="radio" wire:model="atributosSelecionados" name="attribute"
                        value='@json(["id" => $attributes["id"], "name" => $attributes["name"]])' 
                        class="mr-2">
                    {{ $attributes['name'] }}
                </label>
            @endif
        @endforeach
    </div>

    <!-- Botão para selecionar as variações -->
    <button wire:click="selectVariations" id='selectVariations'
        class="fa fa-refresh p-2 mt-1 bg-amber-200 text-white rounded-md hover:bg-amber-300 hover:text-black transition duration-300"
        type="button">
        Selecionar Variações
    </button>

    <!-- Botão para reiniciar os atributos -->
    <button wire:click="resetAttributes" id='resetAttributes'
        class="fa fa-refresh p-2 mt-1 bg-red-200 text-white rounded-md hover:bg-red-300 hover:text-black transition duration-300"
        type="button">
        Reiniciar Atributos
    </button>
</div>



            @if (!empty($variationsData))
                <div class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                    @foreach ($variationsData as $atributo)
                        <div class="mb-4">
                            <h2 class="font-semibold text-gray-700 mb-2">{{ $atributo['attribute']['name'] }}</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                                @foreach ($atributo['terms'] as $atributoTerms)
                                    <label class="block px-4 py-2 cursor-pointer hover:bg-gray-200 flex items-center">
                                        <input type="checkbox" wire:model="termosSelecionados" name="terms[]" value='@json(["id" => $atributo["attribute"]["id"], "name" => $atributoTerms->name])' class="mr-2 checkbox-item" />
                                        {{ $atributoTerms->name }}
                                    </label>
                                @endforeach
                                <!-- Botão Selecionar Todos -->
                                <button type="button" wire:click="selectAll({{ $atributo['attribute']['id'] }})" class="selectAllBtn px-4 py-2 bg-blue-500 text-white rounded mt-2 w-min"
                               >

                                    Selecionar Todos
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Botão de Envio -->
            <button wire:click="generateProducts" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200" type="button">
                Gerar Produtos
            </button>
        
    </div>





</main>
@if (isset($requestData['combination']) || isset($requestData['single']))
<form action="" method="POST" enctype="multipart/form-data">
    <div class="flex justify-center mt-6">
        <button
            class="cursor-pointer bg-gradient-to-b from-yellow-400 to-yellow-500 shadow-[0px_4px_32px_0_rgba(99,102,241,.70)] px-6 py-3 rounded-xl border-[1px] border-yellow-400 text-white font-medium group"
        >
            <div class="relative overflow-hidden">
                <p
                    class="group-hover:-translate-y-7 duration-[1.125s] ease-[cubic-bezier(0.19,1,0.22,1)]"
                >
                    Enviar
                </p>
                <p
                    class="absolute top-7 left-0 group-hover:top-0 duration-[1.125s] ease-[cubic-bezier(0.19,1,0.22,1)]"
                >
                    Enviar
                </p>
            </div>
        </button>
    </div>
    
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">{{ $nomeProduto . " " . $brand }}</h2>
        <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-left">
                    <th class="p-2 border">Produto</th>
                    <th class="p-2 border">Preço</th>
                    <th class="p-2 border">Quantidade</th>
                    <th class="p-2 border">Estoque</th>
                    <th class="p-2 border">Estante</th>
                    <th class="p-2 border">Prateleira</th>
                    <th class="p-2 border">Imagem</th>
                </tr>
            </thead>
            <tbody>
                {{-- Para combinação de cor + tamanho --}}
                @if (isset($requestData['combination']))
                    @foreach ($requestData['combination'] as $idTamanho => $combinacoes)
                        @foreach ($combinacoes as $index => $combinacao)
                            <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }} border-b">
                                <td class="p-2 border font-semibold text-gray-800">
                                    {{ $combinacao[0] . " " . $combinacao[1] }}
                                </td>
                                @foreach (['preco', 'quantidade', 'estoque', 'estante', 'prateleira'] as $field)
                                    <td class="p-2 border">
                                        <input type="text" name="{{ $field }}[]" placeholder="Digite o {{ $field }}" class="w-full p-1 border rounded-md focus:ring-2 focus:ring-indigo-500">
                                    </td>
                                @endforeach
                                <td class="p-2 border">
                                    <input type="file" name="imagem[]" accept="image/*" class="w-full p-1 border rounded-md focus:ring-2 focus:ring-indigo-500">
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif

                {{-- Para lista simples de cores/tamanhos --}}
                @if (isset($requestData['single']))
                    @foreach ($requestData['single'] as $index => $item)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }} border-b">
                            <td class="p-2 border font-semibold text-gray-800">
                                {{ $item }}
                            </td>
                            @foreach (['preco', 'quantidade', 'estoque', 'estante', 'prateleira'] as $field)
                                <td class="p-2 border">
                                    <input type="text" name="{{ $field }}[]" placeholder="Digite o {{ $field }}" class="w-full p-1 border rounded-md focus:ring-2 focus:ring-indigo-500">
                                </td>
                            @endforeach
                            <td class="p-2 border">
                                <input type="file" name="imagem[]" accept="image/*" class="w-full p-1 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</form>
@endif



</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   // Evento para quando o nome do produto ou categoria não forem selecionados
window.addEventListener('no-product-name-or-category', function(event) {
    Swal.fire({
        title: 'Nome do produto ou categoria não selecionados',
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });
});

// Evento para quando não houver atributo selecionado
window.addEventListener('no-selected-attr', function(event) {
    Swal.fire({
        title: 'Nenhum atributo foi selecionado',
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });
});

// Evento para quando não houver cor ou atributo selecionado
window.addEventListener('no-selected-color-or-attr', function(event) {
    Swal.fire({
        title: 'Nenhuma cor ou atributo foi selecionado',
        icon: 'warning',
        confirmButtonText: 'Entendido'
    });
});

</script>

<script>
const brands = [
    // Roupas e sapatos
    "Nike", "Adidas", "Puma", "Reebok", "Vans", "Converse", "New Balance", "Asics", "Under Armour", "Fila",
    "Lacoste", "Timberland", "Balenciaga", "Louis Vuitton", "Gucci", "Prada", "Versace", "Dolce & Gabbana",
    "Burberry", "Tommy Hilfiger", "Ralph Lauren", "Calvin Klein", "Diesel", "Levi's", "Off-White", "Supreme",

    // Perfumes
    "Chanel", "Dior", "Yves Saint Laurent", "Hugo Boss", "Armani", "Carolina Herrera", "Jean Paul Gaultier",
    "Paco Rabanne", "Givenchy", "Hermès", "Bvlgari", "Montblanc", "Lancôme", "Narciso Rodriguez", "Thierry Mugler",
    "Calvin Klein Perfume", "Dolce & Gabbana Perfume", "Versace Perfume"
];

const input = document.getElementById("brandInput");
const list = document.getElementById("brandList");

function filterBrands() {
    const query = input.value.toLowerCase();
    list.innerHTML = "";

    if (query === "") {
        list.classList.add("hidden");
        return;
    }

    const filteredBrands = brands.filter(brand => brand.toLowerCase().includes(query));

    if (filteredBrands.length === 0) {
        list.classList.add("hidden");
        return;
    }

    filteredBrands.forEach(brand => {
        const li = document.createElement("li");
        li.textContent = brand;
        li.className = "px-4 py-2 cursor-pointer hover:bg-gray-200";
        li.onclick = () => selectBrand(brand);
        list.appendChild(li);
    });

    list.classList.remove("hidden");
}

function selectBrand(brand) {
    input.value = brand;
    // Dispara um evento 'input' para notificar o Livewire
    input.dispatchEvent(new Event('input'));
    list.classList.add("hidden");
}


document.addEventListener("click", (event) => {
    if (!input.contains(event.target) && !list.contains(event.target)) {
        list.classList.add("hidden");
    }
});
</script>

<script>
const brandInput = document.getElementById("brandInput");
const productName = document.getElementById("product-name");

// Função para capitalizar a primeira letra de cada palavra, incluindo caracteres acentuados
function capitalizeInput(event) {
    const inputValue = event.target.value;

    // Dividindo o texto em palavras, capitalizando cada uma e juntando de volta
    event.target.value = inputValue
        .split(/\s+/) // Divide o texto por espaços
        .map(word => {
            return word.charAt(0).toLocaleUpperCase() + word.slice(1).toLocaleLowerCase(); // Capitaliza a primeira letra e mantém o restante em minúsculas
        })
        .join(" "); // Junta as palavras de volta
}

// Adicionando o evento 'input' ao input
productName.addEventListener("input", capitalizeInput);
brandInput.addEventListener("input", capitalizeInput);
</script>

<script>
function preencherInput(valor) {
    document.getElementById("attributesInput").value = valor;
}
function mostrarAttrlist() {
    document.getElementById("attrlist").classList.remove("hidden");
}

// Função para esconder a lista ao perder o foco do input
function ocultarAttrlist() {
    setTimeout(function() {  // Adiciona um pequeno delay para não fechar imediatamente
        document.getElementById("attrlist").classList.add("hidden");
    }, 150);  // Delay de 150ms para não fechar imediatamente após o clique
}
</script>