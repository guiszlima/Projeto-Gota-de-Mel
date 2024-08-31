@extends("layouts.main")

@section('content')

@php
$tipoProduto = $product->type ?? $product[0]->type;
$variante = 'true'
@endphp

<div class="flex items-center  justify-center h-100% bg-gray-100">
    <div class="w-[80vw] h-max  mt-[5%] bg-white shadow-lg rounded-lg p-8 flex flex-col items-center">
        <form method="POST" action="{{ route('stock.update') }}" class="w-full flex flex-col items-center">
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

            @if ($tipoProduto !== 'variation')
            @php
            $variante = 'falso';
            @endphp
            <div class="flex flex-col items-center">
                <img src="{{ $product->images[0]->src ?? ""}}" alt="{{ $product->name }}"
                    class="w-1/2 h-auto object-contain mb-6 rounded shadow">
                <input type="text" name="name" readonly value="{{ $product->name }}"
                    class="editInput text-center text-xl font-semibold border border-gray-300 p-3 rounded w-full mb-4">
            </div>

            <div class="flex flex-row justify-between w-50% space-x-10 mt-10">
                <div class="flex flex-col w-1/2">
                    <label for="sku" class="text-gray-700 mb-2">Identificador de Produto</label>
                    <input id="sku" name="sku" type="text" readonly value="{{ $product->sku }}"
                        class="editInput text-center border border-gray-300 p-3 rounded w-full">
                </div>

                <div class="flex flex-col w-1/2">
                    <label for="price" class="text-gray-700 mb-2">Preço</label>
                    <input id="price" name="price" type="text" readonly value="{{ $product->price }}"
                        class="editInput text-center border border-gray-300 p-3 rounded w-full">
                </div>
                <div class="flex flex-col w-1/2">
                    <label for="price" class="text-gray-700 mb-2">Quantidade: </label>
                    <input id="quantity" name="quantity" type="number" readonly value="{{ $product->stock_quantity }}"
                        class="editInput text-center border border-gray-300 p-3 rounded w-full">
                </div>
                <input name="id" type="hidden" readonly value="{{ $product->id }}">
                @else

                <div class="flex flex-col ">
                    <div id="divChangePrices" class="flex flex-row hidden ">
                        <input type="number" id="inputChangePrice"
                            class="text-center text-xl font-semibold border border-gray-300 p-3 rounded w-2/5 mb-4"
                            placeholder="
                Mudar todos valores ">
                        <button id="changeButton" type="button"
                            class="bg-blue-500 text-white px-4 py-3 h-max ml-2 rounded hover:bg-blue-600 transition-colors duration-300">
                            Atualizar
                        </button>
                    </div>
                    <div class="flex flex-wrap  gap-6">
                        @foreach ($product as $variant)

                        <div class=" flex flex-col items-center">
                            <button type="button"
                                class="toggleBtn w-80 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">{{ $variant->name }}</button>
                            <div
                                class="content hidden w-80 mt-4 p-4 bg-white shadow-lg rounded-lg flex flex-col items-center justify-center text-lg fade-in">
                                <img src="{{ $variant->image->src ??'' }}" alt="{{ $variant->name }}"
                                    class="w-3/4 h-auto object-contain mb-4 rounded-lg shadow-md">
                                <input type="text" name="variant_name[]" readonly value="{{ $variant->name }}"
                                    class="editInputVar   text-center text-xl font-semibold border border-gray-300 p-3 rounded w-full mb-4">

                                <div class="flex flex-col w-full space-y-2">


                                    <div class="flex flex-col w-full">
                                        <label for="sku_{{ $loop->index }}" class="text-gray-700 mb-2">Identificador de
                                            Produto</label>
                                        <input id="sku_{{ $loop->index }}" name="variant_sku[]" type="text" readonly
                                            value="{{ $variant->sku }}"
                                            class="editInputVar text-center border border-gray-300 p-3 rounded w-full">
                                    </div>

                                    <div class="flex flex-col w-full">
                                        <label for="price_{{ $loop->index }}" class="text-gray-700 mb-2">Preço</label>
                                        <input id="price_{{ $loop->index }}" name="variant_price[]" type="text" readonly
                                            value="{{ $variant->price }}"
                                            class="precos  editInputVar text-center border border-gray-300 p-3 rounded w-full">
                                    </div>
                                    <div class="flex flex-col w-full">
                                        <label for="price_{{ $loop->index }}"
                                            class="text-gray-700 mb-2">Quantidade</label>
                                        <input id="price_{{ $loop->index }}" name="variant_stock_quantity[]" type="text"
                                            readonly value="{{ $variant->stock_quantity }}"
                                            class="editInputVar text-center border border-gray-300 p-3 rounded w-full">




                                        <input name="id[]" type="hidden" readonly value="{{ $variant->id }}">
                                        <input name="parent_id" type="hidden" readonly
                                            value="{{ $variant->parent_id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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

            if (input.hasAttribute('readonly')) {

                divChangePrices.classList.remove('hidden');

                input.removeAttribute('readonly');
                editButton.classList.add('edit-mode');
                editButton.classList.add('bg-green-500');
                editButton.classList.remove('bg-blue-500');
            } else {
                input.setAttribute('readonly', true);

                divChangePrices.classList.add('hidden');

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
                editButton.classList.add('edit-mode');
                editButton.classList.add('bg-green-500');
                editButton.classList.remove('bg-blue-500');
            } else {
                input.setAttribute('readonly', true);

                editButton.classList.remove('bg-green-500');
                editButton.classList.add('bg-blue-500');
                editButton.classList.remove('edit-mode');

            }
        });


    });
}
</script>
@endsection