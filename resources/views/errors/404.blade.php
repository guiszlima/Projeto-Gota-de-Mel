<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página não encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <!-- Título 404 -->
        <h1 class="text-9xl font-bold" style="color: #702B56;">404</h1>
        <p class="text-2xl md:text-3xl font-light text-gray-700 mt-4">Oops! Página não encontrada</p>
        <p class="text-md md:text-lg text-gray-500 mt-2">Parece que a página que você está procurando não existe.</p>

        <!-- Botão de Voltar -->
        <a href="{{ url('/') }}"
            class="inline-block mt-6 px-8 py-3 font-semibold text-white rounded shadow-md transition duration-300"
            style="background-color: #702B56;" onmouseover="this.style.backgroundColor='#5c2246'"
            onmouseout="this.style.backgroundColor='#702B56'">
            Voltar para a página inicial
        </a>
    </div>
</body>

</html>