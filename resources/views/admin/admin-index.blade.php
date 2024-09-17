@extends('layouts.main')

@section('content')

<x-button-back :route="route('menu')"></x-button-back>

<div class="flex flex-row justify-around w-1/3 my-10">
    <x-dynamic-link text="Admin" route="admin.index" currentRoute="{{$currentRoute}}" />
    <x-dynamic-link text="Aceitar usuários" route="admin.index.accept" currentRoute="{{$currentRoute}}" />
</div>

<x-input-error :messages="$errors->get('email')" class="mt-2" />
<div class="w-full ml-auto mr-auto overflow-x-auto rounded-lg border border-gray-200">
    <table class="text-center w-full h-full table-auto">
        <thead class="text-center">
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">CPF</th>
                <th scope="col">Cargo</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody class='text-center divide-y divide-gray-200'>
            @foreach($users as $usuario)
            <tr class="border-b dark:border-neutral-500">
                <td class="whitespace-nowrap px-6 py-4">{{ $usuario->name }}</td>
                <td class="whitespace-nowrap px-6 py-4">{{ $usuario->email }}</td>
                <td class="whitespace-nowrap px-6 py-4">{{ $usuario->CPF }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.update', $usuario->id) }}">
                        @csrf
                        @method('PUT')

                        <label for="role-{{ $usuario->id }}" class="sr-only">Escolha um papel:</label>
                        <select name="role" id="role-{{ $usuario->id }}" class="border border-gray-300 rounded p-2"
                            onchange="this.form.submit()">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $role->id === $usuario->role_id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="flex flex-row w-max items-center whitespace-nowrap px-6 py-4">
                    <form class="mx-2" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $usuario->email }}">
                        <button
                            class="w-full py-2 px-2 mb-auto text-base font-semibold leading-6 focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-900 focus:outline-none transition-colors duration-200 rounded-full block bg-transparent hover:bg-green-700 border border-green-700 text-green-700 hover:text-green-200"
                            type="submit">Reset Senha</button>
                    </form>



                    <form class="mx-2" action="{{ route('admin.delete', $usuario->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="user_id" value="{{$usuario->id}}">
                        <button
                            class="mx-2 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full block focus:shadow-outline"
                            type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection