<div>
    <form wire:submit.prevent="fetchProducts">
        <input name="products" type="text" wire:model="searchTerm" placeholder="Search for products">
        <button type="submit">Search</button>
    </form>
    
    @if (!empty($data) && !empty($data['products']))
        <div>
            <h2>Produtos:</h2>
            <form action="" method="POST">
                @csrf
                <ul>
                    @foreach ($data['products'] as $product)
                        @if (isset($data['quantidade'][$product->id]))
                            <li>
                                <strong>Produto:</strong> {{ $product->name }} <br>
                                <strong>Preço:</strong> {{ $product->price * $data['quantidade'][$product->id] }} <br>

                                <strong>Quantidade:</strong> 
                                
                                <button type="button" wire:click="decrement({{ $product->id }})">-</button>
                                
                                {{ $data['quantidade'][$product->id] }}
                                
                                <button type="button" wire:click="increment({{ $product->id }})">+</button>
                               
                                <input type="hidden" name="produtos[{{ $product->id }}][name]" value="{{ $product->name }}">
                                <input type="hidden" name="produtos[{{ $product->id }}][price]" value="{{ $product->price }}">
                                <input type="hidden" name="produtos[{{ $product->id }}][quantity]" value="{{ $data['quantidade'][$product->id] }}">
  

                            </li>
                        @endif
                    @endforeach
                </ul>
                <button type="button" id="paymentButton"  >Definir Pagamento</button>

                    <div id="paymentOptions" >
                        <label>
                            <input type="radio" name="payment_method" value="debit"> Débito
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="credit"> Crédito
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="pix"> Pix
                        </label>
                        <button type="submit">Confirmar Pagamento</button>
            </form>
        </div>
    @endif

</div>
