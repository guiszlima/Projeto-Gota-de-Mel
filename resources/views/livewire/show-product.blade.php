<div>
    <form wire:submit.prevent="fetchProducts">
        <input name="products" type="text" wire:model="searchTerm" placeholder="Search for products">
        <button type="submit">Search</button>
    </form>
    @if (!empty($products))
        <div>
            <h2>Products:</h2>
            <ul>
                @foreach ($products as $product)
                    <li>{{ $product['name'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
