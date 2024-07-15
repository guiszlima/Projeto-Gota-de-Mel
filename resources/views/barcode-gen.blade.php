@extends("layouts.main")

@section('content')
<h1>Produtos</h1>

<table>
   <thead>
      <tr>
         <th>ID</th>
         <th>Nome</th>

         <th>Preço</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($products as $product)
         <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
         </tr>
      @endforeach
   </tbody>
</table>
@dd($products)
<div class="pagination">
   @if ($currentPage > 1)
      <a href="{{ url()->current() }}?page={{ $currentPage - 1 }}">Anterior</a>
   @endif

   @for ($i = 1; $i <= $totalPages; $i++)
      <a href="{{ url()->current() }}?page={{ $i }}" class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
   @endfor

   @if ($currentPage < $totalPages)
      <a href="{{ url()->current() }}?page={{ $currentPage + 1 }}">Próximo</a>
   @endif
</div>
@endsection
