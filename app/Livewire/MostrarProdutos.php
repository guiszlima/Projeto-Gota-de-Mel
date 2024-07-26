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
    
    public $formType = false;

   public function changeFormtype(){
    
    $this->formType = !$this->formType;
    
}
    public function fetchProducts(Client $woocommerce)
    {
    
    if($this->formType === false){
         
        $this->searchTerm = str_replace(' ', ',', $this->searchTerm);
 
       if ($this->searchTerm[-1] !== ','){
        $this->searchTerm .= ',';
       }
        $id_array = explode(",", $this->searchTerm);
        
        // Remove valores vazios e espaços
        $filteredArray = array_filter(array_map('trim', $id_array));
        $this->contagem = array_count_values($filteredArray);
        if (count($filteredArray) > 0) {
            $params = ['include' => $filteredArray];
            $this->products = $woocommerce->get('products', $params);
            
            
            
        } else {
            $this->products = [];
        }
    }
    else{
          
         $this->products = $woocommerce->get('products',['search'=>$this->searchTerm]);
         foreach ($this->products as $product) {
            $this->contagem[$product->id] = 1;
        }
    }
    }

    public function increment($productId)
    {
        if (isset($this->contagem[$productId])) {
            $this->contagem[$productId]++;
        }
    }

    public function decrement($productId)
    {
        if (isset($this->contagem[$productId]) && $this->contagem[$productId] > 0) {
            $this->contagem[$productId]--;
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
