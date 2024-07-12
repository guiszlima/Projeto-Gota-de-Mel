<div>
    <form wire:submit.prevent="fetchProducts">
        <input name="products" type="text" wire:model="searchTerm" placeholder="Search for products">
        <button type="submit">Search</button>
    </form>
    
    @if (!empty($data))
        <div>
            
            <h2>Produtos:</h2>
            <ul>
                
            @foreach ($data['products'] as $product)
                @if (isset($data['quantidade'][$product->id]))
                    <li>
                        <strong>Produto:</strong> {{ $product->name }} <br>
                        <strong>Quantidade:</strong> {{ $data['quantidade'][$product->id] }}
                    </li>
                @endif
            @endforeach
            </ul>
        </div>
    @endif
</div>
