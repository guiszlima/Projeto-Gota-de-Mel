@extends("layouts.main")

@section('content')
<h1>Produtos</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>

            <th>Preço</th>
            <th>Gerar Código de barras</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>
                <form action="{{ route('barcode.generate') }}">
                    @csrf
                    <input type="hidden" name="sku" value="{{ $product->sku }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <button type="submit">Gerar Código de Barras</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination">
    @if ($currentPage > 1)
    <a href="{{ url()->current() }}?page={{ $currentPage - 1 }}">Anterior</a>
    @endif

    @for ($i = 1; $i <= $totalPages; $i++) <a href="{{ url()->current() }}?page={{ $i }}"
        class="{{ $i == $currentPage ? 'text-orange-600' : '' }}">{{ $i }}</a>
        @endfor

        @if ($currentPage < $totalPages) <a href="{{ url()->current() }}?page={{ $currentPage + 1 }}">Próximo</a>
            @endif
</div>
@endsection