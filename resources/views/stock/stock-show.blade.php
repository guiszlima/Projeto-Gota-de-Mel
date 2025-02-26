@extends("layouts.main")

@section('content')

@php
$tipoProduto = $product->type ?? $product[0]->type;
$variante = 'true'
@endphp
<x-button-back :route="route('stock.index')"></x-button-back>
<div class="flex items-center  justify-center h-100% bg-gray-100">

    <div class="w-[80vw] h-max  mt-[5%] bg-white shadow-lg rounded-lg p-8 flex flex-col items-center">
        <x-warning :warn="session('warn')" />
        <form method="POST" action="{{ route('stock.update') }}" class="w-full flex flex-col items-center"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')



            <div class=" flex flex-row w-full">
                <div class="flex justify-start w-1/2 mb-5">
                    <button id="saveButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        type="submit">Salvar</button>
                </div>
                <div class="flex justify-end w-1/2 mb-5">
                    <button type="button" id="editButton"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Editar</button>
                </div>
            </div>
<!-- Não é variação -->
            @if ($tipoProduto !== 'variation')
            @php
            $variante = 'falso';
            @endphp

            <div id="divChangeImg" class="flex flex-row hidden ">
                <input name="image" type="file"
                    class="text-center text-xl font-semibold border border-gray-300 p-3 rounded w-min mb-4" placeholder="
            Inserir imagem ">

            </div>

            <div class="flex flex-col items-center">
                <img src="{{ $product->images[0]->src ?? ""}}" alt="{{ $product->name }}"
                    class="w-1/2 h-auto object-contain mb-6 rounded shadow">
                <input type="text" name="name" readonly value="{{ $product->name }}"
                    class="editInput text-center text-xl font-semibold border border-gray-300 p-3 rounded w-full mb-4">
            </div>

            <div class="flex flex-row justify-between w-50% space-x-10 mt-10">
               
                <div class="flex flex-col w-full">
                    <label for="price" class="text-gray-700 mb-2">Preço</label>
                    <input id="price" name="price" type="text" readonly value="{{ $product->price }}"
                        class="price editInput text-center border border-gray-300 p-3 rounded w-full"
                        oninput="maskFloat(event)">
                </div>

                <div class="flex flex-col">
                    <div class="flex flex-col w-full">
                        <label for="estoque" class="text-gray-700 mb-2">Estoque</label>
                        <input id="estoque" name="estoque" type="text" readonly value="{{ $product->estoque??"" }}"
                            class="editInput text-center border border-gray-300 p-3 rounded w-full">
                    </div>
                    <div class="flex flex-col w-full">
                        <label for="estante" class="text-gray-700 mb-2">Estante</label>
                        <input id="estante" name="estante" type="number" readonly value="{{ $product->estante??""  }}"
                            class="  editInput text-center border border-gray-300 p-3 rounded w-full">
                    </div>

                    <div class="flex flex-col w-full">
                        <label for="prateleira" class=" text-gray-700 mb-2">Prateleira</label>
                        <input id="prateleira" name="prateleira" type="number" readonly
                            value="{{ $product->prateleira??""  }}"
                            class="editInput text-center border border-gray-300 p-3 rounded w-full">
                    </div>
                </div>

                <div class="flex flex-col w-1/2">
                    <label for="price" class="text-gray-700 mb-2">Quantidade: </label>
                    <input id="quantity" name="quantity" type="number" readonly value="{{ $product->stock_quantity }}"
                        class="editInput text-center border border-gray-300 p-3 rounded w-full">
                </div>

                  <!-- Peso -->
                   <div class="flex flex-col space-y-6">
                        <div class="flex flex-col w-full">
                            <label for="peso" class="text-gray-700 mb-2">Peso (g)</label>
                            <input id="peso" name="peso" type="text" readonly value="{{ $product->weight * 1000 }}"
                                class="editInput text-center border border-gray-300 p-3 rounded w-full">
                        </div>

                        <!-- Altura -->
                        <div class="flex flex-col w-full">
                            <label for="altura" class="text-gray-700 mb-2">Altura (cm)</label>
                            <input id="altura" name="altura" type="text" readonly value="{{ $product->dimensions->height ?? '' }}"
                                class="editInput text-center border border-gray-300 p-3 rounded w-full">
                        </div>

                        <!-- Comprimento -->
                        <div class="flex flex-col w-full">
                            <label for="comprimento" class="text-gray-700 mb-2">Comprimento (cm)</label>
                            <input id="comprimento" name="comprimento" type="text" readonly value="{{ $product->dimensions->length ?? '' }}"
                                class="editInput text-center border border-gray-300 p-3 rounded w-full">
                        </div>

                        <!-- Largura -->
                        <div class="flex flex-col w-full">
                            <label for="largura" class="text-gray-700 mb-2">Largura (cm)</label>
                            <input id="largura" name="largura" type="text" readonly value="{{ $product->dimensions->width ?? '' }}"
                                class="editInput text-center border border-gray-300 p-3 rounded w-full">
                        </div>
                </div>
                <input name="id" type="hidden" readonly value="{{ $product->id }}">
                <input type="hidden" readonly name="type" value="simple">
<!-- É variação -->
@else
<div class="flex flex-col">
    <div id="divChangePrices" class="flex flex-row hidden mb-6">
        <input type="number" id="inputChangePrice"
            class="text-center text-xl font-semibold border border-gray-300 p-3 rounded-md w-2/5 mb-4 focus:ring-2 focus:ring-blue-500"
            placeholder="Mudar todos preços">
        <button id="changeButton" type="button"
            class="bg-blue-500 text-white px-4 py-3 rounded-md hover:bg-blue-600 transition-colors duration-300 focus:ring-2 focus:ring-blue-500">
            Atualizar
        </button>
    </div>

    <!-- Input único para o nome do produto pai -->
    <div class="flex flex-col w-full mb-8">
        <label for="parent_name" class="text-gray-700 font-semibold mb-2">Nome do Produto Pai</label>
        <input id="parent_name" name="parent_name" type="text" readonly value="{{ $parent_name }}"
            class="editInputVar text-center border border-gray-300 p-3 rounded-md w-full font-semibold bg-gray-100">
    </div>

    <div class="flex flex-wrap gap-6">
    @foreach ($product as $variant)
    <div class="flex flex-col items-center w-full lg:w-1/4 md:w-1/2 sm:w-full min-h-[80px] relative overflow-hidden">
        <!-- Botão de toggle com tamanho fixo -->
        <button type="button"
            class="toggleBtn w-[320px] px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-300">
            {{ $variant->name }}
        </button>

        <!-- Conteúdo (inputs) -->
        <div class="content hidden w-full mt-4 p-6 bg-white shadow-xl rounded-lg text-lg fade-in">
            <!-- Imagem -->
            <img src="{{ $variant->image->src ?? '' }}" alt="{{ $variant->name }}"
                class="w-3/4 h-auto object-contain mb-6 rounded-lg shadow-md mx-auto">

            <!-- Grid de inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Preço -->
                <div class="flex flex-col">
                    <label for="price_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Preço</label>
                    <input id="price_{{ $loop->index }}" name="variant_price[]" type="text" readonly
                        value="{{ $variant->price }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Quantidade -->
                <div class="flex flex-col">
                    <label for="stock_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Quantidade</label>
                    <input id="stock_{{ $loop->index }}" name="variant_stock_quantity[]" type="text" readonly
                        value="{{ $variant->stock_quantity }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Estoque -->
                <div class="flex flex-col">
                    <label for="estoque_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Estoque</label>
                    <input id="estoque_{{ $loop->index }}" name="estoque[]" type="text" readonly
                        value="{{ $variant->estoque }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Estante -->
                <div class="flex flex-col">
                    <label for="estante_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Estante</label>
                    <input id="estante_{{ $loop->index }}" name="estante[]" type="number" readonly
                        value="{{ $variant->estante }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Prateleira -->
                <div class="flex flex-col">
                    <label for="prateleira_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Prateleira</label>
                    <input id="prateleira_{{ $loop->index }}" name="prateleira[]" type="number" readonly
                        value="{{ $variant->prateleira }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Peso -->
                <div class="flex flex-col">
                    <label for="peso_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Peso (g)</label>
                    <input id="peso_{{ $loop->index }}" name="peso[]" type="text" readonly
                        value="{{ number_format(floatval($variant->weight) * 1000, 0, ',', '.') }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Comprimento -->
                <div class="flex flex-col">
                    <label for="comprimento_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Comprimento (cm)</label>
                    <input id="comprimento_{{ $loop->index }}" name="comprimento[]" type="text" readonly
                        value="{{ $variant->dimensions->length }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Largura -->
                <div class="flex flex-col">
                    <label for="largura_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Largura (cm)</label>
                    <input id="largura_{{ $loop->index }}" name="largura[]" type="text" readonly
                        value="{{ $variant->dimensions->width }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Altura -->
                <div class="flex flex-col">
                    <label for="altura_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Altura (cm)</label>
                    <input id="altura_{{ $loop->index }}" name="altura[]" type="text" readonly
                        value="{{ $variant->dimensions->height }}"
                        class="editInputVar text-center border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>

                <!-- Input para imagem -->
                <div class="flex flex-col col-span-1 md:col-span-2">
                    <label for="image_{{ $loop->index }}" class="text-gray-700 font-semibold mb-2">Inserir Imagem</label>
                    <input name="images[{{ $loop->index }}]" type="file" id="image_{{ $loop->index }}"
                        class="text-center text-xl font-semibold border-2 border-gray-200 p-3 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                </div>
            </div>

            <!-- Inputs Hidden -->
            <input name="id[]" type="hidden" readonly value="{{ $variant->id }}">
            <input name="variant_name[]" type="hidden" readonly value="{{ $variant->name }}">
        </div>
    </div>
@endforeach


                    <input type="hidden" readonly name="type" value="variation">
                    <input name="parent_id" type="hidden" readonly value="{{ $parent_id }}">
                    <input type="hidden" readonly name="old_parent_name" value="{{$parent_name}}">
    </div>
</div>
@endif


        </form>
    </div>
</div>


<script>
// Seleciona todos os botões com a classe toggleBtn
const toggleButtons = document.querySelectorAll('.toggleBtn');

toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Encontra a div content que é irmã do botão clicado
        const contentDiv = this.nextElementSibling;

        // Alterna a classe hidden
        contentDiv.classList.toggle('hidden');
    });
});

const editButton = document.getElementById('editButton');
const editInputsVar = document.querySelectorAll('.editInputVar');
const editInputs = document.querySelectorAll('.editInput');
const inputChangePrices = document.getElementById('inputChangePrice');
const changeButton = document.getElementById('changeButton');
const prices = document.querySelectorAll('.precos');
const divChangePrices = document.getElementById('divChangePrices');
const divChangeImgVar = document.getElementById('divChangeImgVar');
const divChangeImg = document.getElementById('divChangeImg');


if ("{{$variante}}" !== "falso") {
    changeButton.addEventListener('click', () => {
        prices.forEach(price => {
            price.value = inputChangePrices.value
            console.log("produto alterado")
        });
    })

    editButton.addEventListener('click', () => {
        const isReadOnly = editInputsVar[0].hasAttribute('readonly');

        editInputsVar.forEach(input => {
            console.log(divChangeImg)

            if (input.hasAttribute('readonly')) {
                if (divChangeImgVar) {
                    divChangeImgVar.classList.remove('hidden');
                    divChangePrices.classList.remove('hidden');
                }
                if (divChangeImg) {

                }

                input.removeAttribute('readonly');

                editButton.classList.add('edit-mode');
                editButton.classList.add('bg-green-500');
                editButton.classList.remove('bg-blue-500');
            } else {
                input.setAttribute('readonly', true);
                if (divChangeImgVar) {
                    divChangePrices.classList.add('hidden');
                    divChangeImgVar.classList.add('hidden');
                }
                if (divChangeImg) {

                }



                editButton.classList.remove('bg-green-500');
                editButton.classList.add('bg-blue-500');
                editButton.classList.remove('edit-mode');

            }
        });


    });
} else {
    editButton.addEventListener('click', () => {
        const isReadOnly = editInputs[0].hasAttribute('readonly');

        editInputs.forEach(input => {

            if (input.hasAttribute('readonly')) {

                input.removeAttribute('readonly');
                divChangeImg.classList.remove('hidden')
                console.log('apareceu')
                editButton.classList.add('edit-mode');
                editButton.classList.add('bg-green-500');
                editButton.classList.remove('bg-blue-500');
            } else {
                input.setAttribute('readonly', true);
                divChangeImg.classList.add('hidden')
                console.log('SUMIU!')
                editButton.classList.remove('bg-green-500');
                editButton.classList.add('bg-blue-500');
                editButton.classList.remove('edit-mode');

            }
        });


    });
}
</script>


<script>
    function maskFloat(e) {
    let value = e.target.value;
    value = value.replace(/\D/g, '');
    value = (value / 100).toFixed(2) + '';
    value = value.replace(".", ",");
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    e.target.value = value;
}
</script>
@endsection