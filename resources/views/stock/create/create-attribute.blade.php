@extends('layouts.main')

@section('content')
<main class="flex flex-col">
    <h1 class="text-2xl font-bold m-6">Criar</h1>
    <div class="flex flex-row justify-around w-1/3 my-10">
        <x-dynamic-link text="Criar Produto" route="stock.create" currentRoute="{{$currentRoute}}" />
        <x-dynamic-link text="Criar Atributo" route="stock.attribute" currentRoute="{{$currentRoute}}" />
    </div>
    <form id="myForm" action="{{ route('stock.store.attribute') }}" method="POST">
        @csrf
        <div class="container mx-auto p-6">
            <div class="mb-4">
                <label for="atributo" class="block text-gray-700 font-bold mb-2">Atributo</label>
                <div class="flex">
                    <input type="text" id="atributo" class=" shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" placeholder="Digite o nome do atributo">
                    <button id="criarAtributo" type="button"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                        Criar Atributo
                    </button>
                </div>
            </div>

            <div id="atributosContainer" class="mt-4"></div>

            <button id="submitData" type="button"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4 focus:outline-none focus:shadow-outline">
                Enviar Dados
            </button>
            <input id="atributoDados" type="hidden" name="atributoDados">
        </div>
    </form>
</main>

<script>
document.getElementById('criarAtributo').addEventListener('click', function() {
    const atributoNome = document.getElementById('atributo').value.trim();

    if (atributoNome === '') {
        alert('Digite o nome do atributo.');
        return;
    }

    const atributosContainer = document.getElementById('atributosContainer');
    const existingAttributes = atributosContainer.querySelectorAll('.atributo-container');

    // Verificar se o atributo já foi adicionado
    for (let i = 0; i < existingAttributes.length; i++) {
        if (existingAttributes[i].dataset.atributo === atributoNome) {
            alert('Atributo já adicionado.');
            return;
        }
    }

    const container = document.createElement('div');
    container.className = 'atributo-container mt-4 border rounded-lg p-4 bg-gray-100';
    container.dataset.atributo = atributoNome;

    const header = document.createElement('div');
    header.className = 'flex justify-between items-center mb-2';

    const titulo = document.createElement('h2');
    titulo.className = 'text-gray-700 font-bold';
    titulo.textContent = `Criar propriedade de ${atributoNome}`;

    const removeAtributoBtn = document.createElement('button');
    removeAtributoBtn.type = 'button';
    removeAtributoBtn.className =
        'bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline';
    removeAtributoBtn.textContent = 'Excluir';
    removeAtributoBtn.addEventListener('click', function() {
        container.remove();
    });

    header.appendChild(titulo);
    header.appendChild(removeAtributoBtn);
    container.appendChild(header);

    const propriedadesContainer = document.createElement('div');
    propriedadesContainer.className = 'propriedades-container';



    const addPropriedadeBtn = document.createElement('button');
    addPropriedadeBtn.type = 'button';
    addPropriedadeBtn.className =
        'bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-2';
    addPropriedadeBtn.textContent = 'Adicionar Nova Propriedade';
    addPropriedadeBtn.addEventListener('click', function() {
        const novoInput = document.createElement('div');
        novoInput.className = 'flex items-center mb-2';

        const inputPropriedade = document.createElement('input');
        inputPropriedade.type = 'text';

        inputPropriedade.placeholder = `Digite o nome da propriedade de ${atributoNome}`;
        inputPropriedade.className =
            'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2 ' +
            atributoNome.replace(/\s+/g, '-').toLowerCase();

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className =
            'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline';
        removeBtn.textContent = 'Excluir';
        removeBtn.addEventListener('click', function() {
            novoInput.remove();
        });

        novoInput.appendChild(inputPropriedade);
        novoInput.appendChild(removeBtn);

        propriedadesContainer.appendChild(novoInput);
    });

    container.appendChild(propriedadesContainer);
    container.appendChild(addPropriedadeBtn);

    atributosContainer.appendChild(container);

    // Limpar o campo de atributo
    document.getElementById('atributo').value = '';
});

document.getElementById('submitData').addEventListener('click', function() {
    const atributosContainer = document.getElementById('atributosContainer').children;
    const data = {};

    for (let i = 0; i < atributosContainer.length; i++) {
        const atributoNome = atributosContainer[i].dataset.atributo;
        const propriedades = atributosContainer[i].querySelectorAll('input:not([type="hidden"])');
        const propriedadesArray = [];

        propriedades.forEach(function(propriedade) {
            const propNome = propriedade.value.trim();
            if (propNome !== '') {
                propriedadesArray.push(propNome);
            }
        });

        if (propriedadesArray.length > 0) {
            // Substitui espaços por hífens para a classe
            const atributoClasse = atributoNome.replace(/\s+/g, '-').toLowerCase();
            data[atributoClasse] = propriedadesArray;
        }
    }

    // Definir o valor do input hidden com os dados JSON
    document.getElementById('atributoDados').value = JSON.stringify(data);

    // Submeter o formulário
    document.getElementById('myForm').submit();
});
</script>
@endsection