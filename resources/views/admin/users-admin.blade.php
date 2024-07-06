@extends('layouts.admin')

@section('content')
<main>
<div class="w-10/12	ml-auto mr-auto  overflow-x-auto rounded-lg border border-gray-200">
    
        <table class="text-left w-full h-full table-auto">
            <thead class="text-center">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody class='text-center divide-y divide-gray-200'>
                @foreach($usuarios_pendentes as $usuario)
                    <tr class="border-b dark:border-neutral-500"></tr>
                        <td class="whitespace-nowrap px-6 py-4">{{ $usuario->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4" >{{ $usuario->email }}</td>
                        <td class="whitespace-nowrap px-6 py-4">{{ $usuario->CPF }}</td>
                        <td>{{ $usuario->is_pending ? 'Pendente' : 'Autorizado' }}</td>
                       
                        <td class=" flex flex-row w-max  whitespace-nowrap px-6 py-4">
                            <form action="{{ route('admin.accept') }}" method="POST">
                                @csrf
                                @method('PUT')
                               
                                <label for="role">Escolha um papel:</label>
                                <select name="role" id="role">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                
                                <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                                <button class="mx-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Aceitar</button>
                            </form>
                            <form action="{{ route('admin.delete', $usuario->id) }}" method="POST" >
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="user_id" value="{{$usuario->id}}">
                                <button class="mx-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  type="submit">Negar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
@endsection
