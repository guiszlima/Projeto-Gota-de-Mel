@extends('layouts.main')

@section('content')
<main class="flex flex-col">
    <h1 class="text-2xl font-bold m-6">Criar</h1>
    <div class="flex flex-row justify-around w-1/3 my-10">
        <x-dynamic-link text="Produto sem Variações" route="{{$currentRoute}}" currentRoute="{{$currentRoute}}" />
        <x-dynamic-link text="Produto com Variações" route="stock.create.var-product"
            currentRoute="{{$currentRoute}}" />

    </div>
    <form action="{{ route('stock.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="w-full max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">
                    Nome <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite o nome do produto" required>
            </div>

            <div class="mb-4">
                <label for="sku" class="block text-gray-700 font-bold mb-2">
                    Identificador de Produto <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-row">
                    <input readonly type="text" id="sku" name="sku"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Digite o SKU" required>
                    <button id="generateSku"
                        class="fa fa-refresh w-max h-max p-2 ml-4 bg-amber-200 text-white rounded-full hover:bg-amber-300 hover:text-black transition duration-300"
                        type="button">
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-bold mb-2">
                    Preço <span class="text-red-500">*</span>
                </label>
                <input type="text" id="price" name="price"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite o preço" required>
            </div>

            <div class="mb-4">
                <label for="stock_quantity" class="block text-gray-700 font-bold mb-2">
                    Quantidade em Estoque <span class="text-red-500">*</span>
                </label>
                <input type="number" id="stock_quantity" name="stock_quantity"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite a quantidade em estoque" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-bold mb-2">
                    Categoria <span class="text-red-500">*</span>
                </label>
                <select id="category" name="category"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">
                    Descrição
                </label>
                <textarea id="description" name="description"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite a descrição do produto" rows="5"></textarea>
            </div>

            <div class="mb-4">
                <label for="height" class="block text-gray-700 font-bold mb-2">
                    Altura (cm) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="height" name="height"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite a altura do produto" required>
            </div>

            <div class="mb-4">
                <label for="width" class="block text-gray-700 font-bold mb-2">
                    Largura (cm) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="width" name="width"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite a largura do produto" required>
            </div>

            <div class="mb-4">
                <label for="depth" class="block text-gray-700 font-bold mb-2">
                    Profundidade (cm)
                </label>
                <input type="number" id="depth" name="depth"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite a profundidade do produto">
            </div>

            <div class="mb-4">
                <label for="weight" class="block text-gray-700 font-bold mb-2">
                    Peso (kg) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="weight" name="weight" step="0.01"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Digite o peso do produto" required>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-bold mb-2">
                    Imagem
                </label>
                <input type="file" id="image" name="image"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    accept="image/*">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Salvar Produto
                </button>
            </div>
        </div>
    </form>
</main>

<script>
document.getElementById("generateSku").addEventListener("click", function() {

    // Função para gerar SKU único
    function generateSku() {
        now = new Date();
        const year = now.getFullYear();
        const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Mês começa em 0
        const day = now.getDate().toString().padStart(2, '0');
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const milliseconds = now.getMilliseconds().toString().padStart(3, '0');

        const timeString = `${year}${month}${day}${hours}${minutes}${seconds}${milliseconds}`
        let sku = timeString;

        return sku;
    }

    // Preenche o campo SKU com o SKU gerado
    document.getElementById("sku").value = generateSku();
});

document.getElementById("price").addEventListener("input", function(e) {
    let value = e.target.value;
    value = value.replace(/\D/g, ''); // Remove tudo que não for dígito
    value = (value / 100).toFixed(2) + ''; // Formata o número para 2 casas decimais
    value = value.replace(".", ","); // Substitui o ponto por vírgula
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // Adiciona os pontos de milhar
    e.target.value = value;
});
</script>
@endsection