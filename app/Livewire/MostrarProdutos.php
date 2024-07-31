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
          
         $this->products = $woocommerce->get('products',['search'=>$this->searchTerm]);
         foreach ($this->products as $product) {
            $this->contagem[$product->id] = 1;
        }
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