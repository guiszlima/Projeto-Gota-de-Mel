<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class MostrarProdutos extends Component
{
    public $searchTerm;
    public $products = [];

   
    public function fetchProducts(Client $woocommerce)
    {
       
        $id_array = explode(",", $this->searchTerm);
        
        // Remove valores vazios e espaços
        $filteredArray = array_filter(array_map('trim', $id_array));
        
        if (count($filteredArray) > 0) {
            $params = ['include' => $filteredArray];
            $this->products = $woocommerce->get('products', $params);
            dd($this->products);
            
        } else {
            $this->products = [];
        }
    }
    public function render()
    {
        return view('livewire.mostrar-produtos', ['products' => $this->products]);
    }
}
