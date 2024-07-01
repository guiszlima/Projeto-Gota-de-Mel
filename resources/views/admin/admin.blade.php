<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuários Pendentes</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Lista de Usuários</h1>
    <div class=" w-1/2 mx-auto bg-white p-4 rounded-lg shadow-lg overflow-y-auto overflow-x-hidden h-72">
        <table class="w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($usuarios_pendentes as $user)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $user->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
