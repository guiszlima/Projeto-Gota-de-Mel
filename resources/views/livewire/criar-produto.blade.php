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
                <input required type="text" id="product-name" name="product_name" wire:model="nome_produto"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
            </div>

            <!-- Marca do Produto -->
            <div class="relative mb-5">
                <label for="brandInput" class="block text-sm font-semibold text-gray-700">Marca</label>
                <input type="text" id="brandInput" placeholder="Digite uma marca..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
                    oninput="filterBrands()">
                <ul id="brandList"
                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 hidden max-h-48 overflow-y-auto shadow-lg transition-all duration-200">
                    <!-- Opções do select via JavaScript -->
                </ul>
            </div>
            <div class="mb-4">
                <label for="product-description" class="block text-sm font-medium text-gray-700">Descrição do
                    Produto</label>
                <textarea id="product-description"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
                    rows="4"></textarea>
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
            
            <!-- Botão de Envio -->
            <button 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200">
                Gerar Produtos
            </button>



        </form>
    </div>
</main>


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
            return word.charAt(0).toLocaleUpperCase() + word.slice(1)
        .toLocaleLowerCase(); // Capitaliza a primeira letra e mantém o restante em minúsculas
        })
        .join(" "); // Junta as palavras de volta
}

// Adicionando o evento 'input' ao input
productName.addEventListener("input", capitalizeInput);
brandInput.addEventListener("input", capitalizeInput);
</script>