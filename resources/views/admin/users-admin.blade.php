@extends('layouts.admin')

@section('content')
    <div class="container">
      

        <table class="text-left w-full">
            <thead class="bg-black flex text-white w-full">
                <tr class="flex w-full mb-4">
                    <th class="p-4 w-1/4">Name</th>
                    <th class="p-4 w-1/4">Email</th>
                    <th class="p-4 w-1/4">CPF</th>
                    <th class="p-4 w-1/4">Status</th>
                </tr>
            </thead>
            <!-- Remove the nasty inline CSS fixed height on production and replace it with a CSS class — this is just for demonstration purposes! -->
            <tbody class="bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="height: 50vh;">
                @foreach($usuarios_pendentes as $usuario)
                
                    <tr class="flex w-full mb-4">
                        <td class="p-4 w-1/4">{{ $usuario->name }}</td>
                        <td class="p-4 w-1/4">{{ $usuario->email }}</td>
                        <td class="p-4 w-1/4">{{ $usuario->CPF }}</td>
                        <td class="p-4 w-1/4">{{ $usuario->is_pending ? 'Autorizado'  : 'Pendente' }}</td>
                    <td>
                        <form action="{{route('admin')}}" method="POST" class="bg-sky-500 hover:bg-sky-700 rounded-lg">
                    @csrf
                    @method('PUT')
                    <label for="role">Escolha um papel:</label>
                    <select name="role" id="role">
                        @foreach ($roles as $role )
                        <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                        
                    </select>
                    <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                    <button type="submit">Aceitar</button>
                    </td>
                </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
