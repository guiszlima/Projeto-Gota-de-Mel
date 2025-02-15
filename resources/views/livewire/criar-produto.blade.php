<main class="flex flex-col items-center p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Criar Produto</h1>

    <x-warning :warn="session('warn')" />

    <div class="w-full max-w-lg bg-white p-6 rounded-xl shadow-lg">
        <form id="myForm" action="{{ route('stock.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @isset ($mensagem)
            <div class="my-4 p-3 bg-red-100 border border-red-400 text-red-600 rounded-md">
                {{ $mensagem }}
            </div>
            @endisset

            <!-- Nome do Produto -->
            <div class="mb-5">
                <label for="product-name" class="block text-sm font-semibold text-gray-700">Nome do Produto</label>
                <input required type="text" id="product-name" name="product_name" wire:model="nomeProduto"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
            </div>

            <!-- Marca do Produto -->
            <div class="relative mb-5">
                <label for="brandInput" class="block text-sm font-semibold text-gray-700">Marca</label>
                <input type="text" id="brandInput" wire:model="brand" placeholder="Digite uma marca..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
                    oninput="filterBrands()">
                <ul id="brandList"
                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 hidden max-h-48 overflow-y-auto shadow-lg transition-all duration-200">
                    <!-- Opções do select via JavaScript -->
                </ul>
            </div>

            <!-- Descrição do Produto -->
            <div class="mb-4">
                <label for="product-description" class="block text-sm font-medium text-gray-700">Descrição do Produto</label>
                <textarea id="product-description" name="product_description"
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

            <!-- Atributos -->
            <div class="relative mb-5">
                <label for="attributesInput" class="block text-sm font-semibold text-gray-700">Atributos</label>
                <div id="attributesInput" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                    @foreach($attr as $attributes)
                        <label class="block px-4 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="checkbox" wire:model="atributosSelecionados" name="attributes[]" value='@json(["id" => $attributes["id"], "name" => $attributes["name"]])' class="mr-2">
                            {{ $attributes['name'] }}
                        </label>
                    @endforeach
                </div>
                <button wire:click="selectVariations" id='selectVariations'
                    class="fa fa-refresh p-2 mt-1 bg-amber-200 text-white rounded-md hover:bg-amber-300 hover:text-black transition duration-300"
                    type="button">
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
                                onclick="selecionarTodos">

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
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.addEventListener('no-selected-attr', function(event) {
        Swal.fire({
            title: 'Nenhum atributo foi selecionado',
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