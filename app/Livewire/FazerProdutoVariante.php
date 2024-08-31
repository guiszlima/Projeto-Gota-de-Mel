<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Route;




class FazerProdutoVariante extends Component
{
    public $nome_produto;
    public $atributo_pai;
    public $attr = []; // Define como array simples
    public $escolhido = false;
    public $mensagem;
    public $currentRoute;
    public $categories;

    public $categoryValue;


 
    public function updatedAtributoPai($value)
    {
        $this->atributo_pai = json_decode($value, true);
    
    
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
    // Método para requisitar atributos do WooCommerce
    public function ReqAttributes(Client $woocommerce)
    {
        

        $productAttributes = $woocommerce->get('products/attributes');
       
        $organizedAttributes = [];

        // Iterar sobre os atributos para buscar seus termos
        foreach ($productAttributes as $attribute) {
            // Obter os termos de cada atributo
            $terms = $woocommerce->get('products/attributes/' . $attribute->id . '/terms');
            
            // Converter termos para array
            $termsArray = json_decode(json_encode($terms), true);

            // Organizar os atributos e seus termos em um objeto
            $organizedAttributes[] = [
                'nome_pai' => $attribute->name,
                'id_pai' => $attribute->id,
                'atributos_filhos' => array_map(function($term) {
                    return [
                        'id' => $term['id'],
                        'name' => $term['name'],
                    ];
                }, $termsArray)
            ];
        }

        // Definir a propriedade $attributes
        $this->attr = $organizedAttributes;
    }

    // Método para alterar o estado de escolhido
    public function choose()
    {
        if(!$this->nome_produto){
            $this->mensagem = "O nome do produto é invalido";
            return;
        } 
        if(!$this->atributo_pai){
            $this->mensagem="Atributo não selecionado";
            return;
        }
        if(!$this->categoryValue){
            $this->mensagem = "Categoria não selecionada";
            return;
        }
       
        
            $this->mensagem = false;
        
        $this->escolhido = true;
    }

    // Renderizar a view e passar os dados necessários
    public function render()
    {
        return view('livewire.fazer-produto-variante', [
            'attributes' => $this->attr,
            'escolhido' => $this->escolhido
        ]);
    }

    // Método para inicializar o componente
    public function mount(Client $woocommerce)
    {
        $this->currentRoute = Route::currentRouteName();
        $this->ReqAttributes($woocommerce);
        $this->categories = $this->getCategories($woocommerce);
        
    }
}