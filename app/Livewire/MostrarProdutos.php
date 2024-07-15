<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class MostrarProdutos extends Component
{
    public $searchTerm;
    public $products = [];
    public $contagem = [];
   
    public function fetchProducts(Client $woocommerce)
    {
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
        ];
    
        return view('livewire.mostrar-produtos', compact("data"));
    }
}
