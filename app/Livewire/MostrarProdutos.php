<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
use phpDocumentor\Reflection\PseudoTypes\False_;

class MostrarProdutos extends Component
{



    
    public $searchTerm;
    public $products = [];
    public $contagem = [];
    public $cart = [];
    
    public $formType = false;

   public function changeFormtype(){
    
    $this->formType = !$this->formType;
    
}
    public function fetchProducts(Client $woocommerce)
    {
    
    if($this->formType === false){
         
       
         $param = [ 'sku'=> $this->searchTerm];
           
            $pedido = $woocommerce->get('products', $param);
            if(!empty($pedido)){
            $produto = $pedido[0];
            
            $this->addToCart($produto->name,$produto->price,$produto->id);
           
    }
}
    else{
        $this->products = $woocommerce->get('products', ['search' => $this->searchTerm]);

        $productsWithVariations = [];
        
        foreach ($this->products as $product) {
            // Adiciona o produto principal ao array
            $productsWithVariations[] = $product;
        
            // Inicializa a contagem para o produto principal
            $this->contagem[$product->id] = 1;
        
            // Verifica se o produto tem variações
            if ($product->type === 'variable') {
                // Busca as variações do produto
                $variations = $woocommerce->get("products/{$product->id}/variations");
                $daddyIsOff = False;
                // Adiciona cada variação como um produto independente no array
                foreach ($variations as $variation) {
                    // Adiciona algumas propriedades do produto principal à variação
                    $variation->parent_name = $product->name;
                    $variation ->name = $variation->parent_name ." ". $variation->name;
                    $variation->parent_id = $product->id;
                    $productIdsToRemove[] = $variation->parent_id;
                     
                    // Adiciona a variação ao array de produtos
                    $productsWithVariations[] = $variation;

                    // Inicializa a contagem para a variação
                    $this->contagem[$variation->id] = 1;
                }
            }
        }
        $filteredProducts = array_filter($productsWithVariations, function($product) use ($productIdsToRemove) {
            // Remove o produto se o seu ID estiver na lista de IDs a serem removidos
            return !in_array($product->id, $productIdsToRemove);
        });
        // Atualiza $this->products para incluir as variações como produtos independentes
        $this->products = $filteredProducts;
        }
    }
    
    

    public function addToCart($productName, $productValue,$productId){
        // Inicializa uma variável para indicar se o produto já foi encontrado
        $found = false;
    
        // Percorre o carrinho para encontrar o produto
        foreach ($this->cart as &$item) {
            if ($item['id'] === $productId) {
                // Se o produto for encontrado, atualiza a quantidade e o valor
                $item['quantidade'] += 1;
                $item['value'] = $productValue;
                $found = true;
                break;
            }
        }
    
        // Se o produto não foi encontrado, adiciona um novo item ao carrinho
        if (!$found) {
            $cartItem = [
                'id' => $productId,
                'name' => $productName,
                'value' => $productValue,
                'quantidade' => 1
            ];
            $this->cart[] = $cartItem;
        }
    }
    
    public function increment($id){
        
        foreach( $this->cart as &$item) {
        
            if($item['id'] == $id){
                $item['quantidade'] +=1;
               
                break;
                
            }
            
    }
    
}
    public function decrement($id,$qtde){
        


        foreach( $this->cart as $key => &$item ) {
        
            if($item['id'] == $id){
                $item['quantidade'] -=1;
            if($item['quantidade'] < 1 ){
                unset($this->cart[$key]);
            }
                break;
                
            }
        }
            
    
    }





    public function render()
    {
        $data = [
            'quantidade' => $this->contagem,
            'products' => $this->products,
            'formState' => $this->formType,  
        ];
    
        return view('livewire.mostrar-produtos', compact("data"));
    }
}