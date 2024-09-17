@props(['route'])

@php
// Define a rota padrão caso nenhuma rota seja passada
$defaultRoute = url()->previous(); // Defina aqui a sua rota padrão
@endphp

<a href="{{ $route ?? $defaultRoute}}"
    class="flex items-center  w-max bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition-colors duration-300">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
    </svg>
    Voltar
</a>