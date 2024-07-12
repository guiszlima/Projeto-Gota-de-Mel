<div>
    <form wire:submit.prevent="fetchProducts">
        <input name="products" type="text" wire:model="searchTerm" placeholder="Search for products">
        <button type="submit">Search</button>
    </form>
    
    @if (!empty($data) && !empty($data['products']))
        <div>
            <h2>Produtos:</h2>
            <form method="POST">
                @csrf
                <ul>
                    @foreach ($data['products'] as $product)
                        @if (isset($data['quantidade'][$product->id]))
                            <li>
                                <strong>Produto:</strong> {{ $product->name }} <br>
                                <strong>Preço:</strong> {{ $product->price }} <br>
                                <strong>Quantidade:</strong> {{ $data['quantidade'][$product->id] }} <br>
                                <input type="hidden" name="produtos[{{ $product->id }}]" value="{{ $data['quantidade'][$product->id] }}">
                            </li>
                        @endif
                    @endforeach
                </ul>
                <button type="submit">Definir Pagamento</button>
            </form>
        </div>
    @endif
</div>
