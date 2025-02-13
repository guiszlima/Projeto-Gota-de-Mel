<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class CriarProduto extends Component
{
    public $nomeProduto;
    public $categories;
    public $attr;
    public $atributosSelecionados = []; // Armazenar os IDs dos atributos selecionados
    public $termosSelecionados = []; // Armazenar os IDs dos termos selecionados
    public $variationsData = []; // Armazenar os dados das variações
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


public function selectVariations(Client $woocommerce)
{
    // Verifica se há atributos selecionados
    if (empty($this->atributosSelecionados)) {
        $this->dispatch('no-selected-attr');
        return;
    }

    // Reseta o array para evitar duplicações
    $this->variationsData = [];

    // Para cada ID de atributo selecionado, obtém os dados do atributo e os seus termos
    foreach ($this->atributosSelecionados as $attrJson) {
        // Decodifica o JSON para um array associativo
        $attr = json_decode($attrJson, true);

        // Verifica se a decodificação foi bem-sucedida
        if (!$attr || !isset($attr['id'])) {
            continue;
        }

        // Obtem os termos do atributo
        $terms = $woocommerce->get("products/attributes/{$attr['id']}/terms", [
            'per_page' => 100,
        ]);

        // Junta os dados do atributo com os seus termos
        $this->variationsData[] = [
            'attribute' => [
                'id'   => $attr['id'],
                'name' => $attr['name'],
            ],
            'terms' => $terms, // Contém o array de termos relacionados a esse atributo
        ];
    }
   
    // Opcional: Limpa os atributos selecionados se necessário
    $this->atributosSelecionados = [];
}


    // Método para adicionar ou remover um atributo selecionado
  
    public function selectAll($atributoId)
    {
        // Pegue os termos de todos os atributos e adicione à seleção atual
        $novosTermosSelecionados = collect($this->variationsData)
            ->where('attribute.id', $atributoId)
            ->flatMap(function ($atributo) {
                return collect($atributo['terms'])->pluck('name');
            })
            ->all();
    
        // Adicione os novos termos aos já selecionados, sem remover os existentes
        if (count(array_intersect($novosTermosSelecionados, $this->termosSelecionados)) === count($novosTermosSelecionados)) {
            // Se todos os termos do atributo já estiverem selecionados, desmarque-os
            $this->termosSelecionados = array_diff($this->termosSelecionados, $novosTermosSelecionados);
        } else {
            // Caso contrário, adicione-os à seleção
            $this->termosSelecionados = array_unique(array_merge($this->termosSelecionados, $novosTermosSelecionados));
        }
    }
    
public function mount(Client $woocommerce)
{
  
    $this->categories = $this->getCategories($woocommerce);
    $this->attr = $this->getAttr($woocommerce);
}
}
