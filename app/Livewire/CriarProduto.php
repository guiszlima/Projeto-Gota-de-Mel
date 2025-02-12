<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class CriarProduto extends Component
{
    public $categories;
    public function render()
    {
        return view('livewire.criar-produto');
    }

    public function getCategories(Client $woocommerce){
        $categories_data = [];
        $woocategories = $woocommerce->get('products/categories');
        foreach($woocategories as $category){
        $categories_data[] = [
                        'id' =>  $category->id,
                        'name'=>$category->name
        ]; 
        return $categories_data;
    }
}
public function mount(Client $woocommerce)
{
  
    $this->categories = $this->getCategories($woocommerce);
    
}
}
