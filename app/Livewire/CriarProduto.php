<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class CriarProduto extends Component
{
    public $categories;
    public $attr;
    public $atributosSelecionados = []; // Armazenar os IDs dos atributos selecionados
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
       
    }
    
    return $categories_data;
}
public function getAttr(Client $woocommerce){
    $attributes_data = [];
    $wooattributes = $woocommerce->get('products/attributes');
    foreach($wooattributes as $attribute){
    $attributes_data[] = [
                    'id' =>  $attribute->id,
                    'name'=>$attribute->name
    ]; 

}    
    return $attributes_data;

}


public function selectVariations(){

if(empty($this->atributosSelecionados)){
$this->dispatch('no-selected-attr');
};


}



    // Método para adicionar ou remover um atributo selecionado
  

public function mount(Client $woocommerce)
{
  
    $this->categories = $this->getCategories($woocommerce);
    $this->attr = $this->getAttr($woocommerce);
}
}
