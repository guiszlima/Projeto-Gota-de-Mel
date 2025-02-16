<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;

class CriarProduto extends Component
{
    
    public $nomeProduto;
    public $description;
    public $categories;
    public $brand;
    public $attr;
    public $categorySelected;
    public $atributosSelecionados = []; // Armazenar os IDs dos atributos selecionados
    public $termosSelecionados = []; // Armazenar os IDs dos termos selecionados
    public $variationsData = []; // Armazenar os dados das variações
    public $cores ; 
    public $mostrarCores = false;
    public $coresSelecionadas = [];


    public function render()
    {
        return view('livewire.criar-produto');
    }

    public function mount(Client $woocommerce)
    {
        $this->categories = $this->getCategories($woocommerce);
        $this->attr = $this->getAttr($woocommerce);


        $this->cores = $this->getCores($woocommerce); 
        $this->coresSelecionadas = [env("COR_ID") => []];
    }

    public function getCategories(Client $woocommerce)
    {
        $categories_data = [];
        $woocategories = $woocommerce->get('products/categories');
        foreach ($woocategories as $category) {
            $categories_data[] = [
                'id' => $category->id,
                'name' => $category->name
            ];
        }
        return $categories_data;
    }

    public function getAttr(Client $woocommerce)
    {
        $attributes_data = [];
        
        
        
        
            $wooattributes = $woocommerce->get('products/attributes');
        
        
       
        foreach ($wooattributes as $attribute) {
            $attributes_data[] = [
                'id' => $attribute->id,
                'name' => $attribute->name
            ];
        }
        return $attributes_data;
    }
 public function getCores(Client $woocommerce){
    $cor_id = env("COR_ID");
    $cores_data = [];
    $woocores = $woocommerce->get("products/attributes/$cor_id/terms"); // 14 é o Id do atributo cor no Woocomerce
    foreach ($woocores as $cor) {
        $cores_data[] = [
            'id' => $cor->id,
            'name' => $cor->name
        ];
    }
    return $cores_data;
}

public function toggleCores()
    {
        $this->mostrarCores = !$this->mostrarCores;
    }

public function selectColorVars(Client $woocommerce){
    
}

    public function selectVariations(Client $woocommerce)
    {
        // Verifica se um atributo foi selecionado
        if (empty($this->atributosSelecionados)) {
            $this->dispatch('no-selected-attr');
            return;
        }
    
        // Reseta o array para evitar duplicações
        $this->variationsData = [];
    
        // Decodifica o JSON do atributo selecionado
        $attr = json_decode($this->atributosSelecionados, true);
    
        // Verifica se a decodificação foi bem-sucedida
        if (!$attr || !isset($attr['id'])) {
            return;
        }
    
        // Obtem os termos do atributo
        $terms = $woocommerce->get("products/attributes/{$attr['id']}/terms", [
            'per_page' => 100,
        ]);
    
        // Junta os dados do atributo com os seus termos
        $this->variationsData[] = [
            'attribute' => [
                'id' => $attr['id'],
                'name' => $attr['name'],
            ],
            'terms' => $terms,
        ];
    
        // Limpa os termos selecionados ao mudar o atributo
        $this->termosSelecionados = [];
    }
    

    // Método para adicionar ou remover um atributo selecionado
    public function selectAll($atributoId)
    {
        // Pegue os termos de todos os atributos e adicione à seleção atual
        $novosTermosSelecionados = collect($this->variationsData)
            ->where('attribute.id', $atributoId)
            ->flatMap(function ($atributo) {
                return collect($atributo['terms'])->map(fn ($term) => json_encode([
                    'id' => $atributo['attribute']['id'],
                    'name' => $term->name
                ]));
            })
            ->all();

        // Verifica se todos já estão selecionados
        if (count(array_intersect($novosTermosSelecionados, $this->termosSelecionados)) === count($novosTermosSelecionados)) {
            // Se todos os termos do atributo já estiverem selecionados, desmarque-os
            $this->termosSelecionados = array_diff($this->termosSelecionados, $novosTermosSelecionados);
        } else {
            // Caso contrário, adicione-os à seleção
            $this->termosSelecionados = array_unique(array_merge($this->termosSelecionados, $novosTermosSelecionados));
        }
    }
    private function getRequestData()
    {
        $arrayAttr = [];
    
        // Decodifica a categoria selecionada (JSON para array associativo)
        $jsonCategories = json_decode($this->categorySelected, true);
    
        // Percorre os termos selecionados e os converte para array associativo
        foreach ($this->termosSelecionados as $atributo) {
            $jsonAttr = json_decode($atributo, true);
    
            // Adiciona o nome ao array correspondente ao id
            if (isset($arrayAttr[$jsonAttr['id']])) {
                // Se o id já existe, adiciona o nome ao array
                $arrayAttr[$jsonAttr['id']][] = $jsonAttr['name'];
            } else {
                // Se o id não existe, cria um novo array com o nome
                $arrayAttr[$jsonAttr['id']] = [$jsonAttr['name']];
            }
        }
    
        // Ordena o array de atributos pelo ID (ordem crescente)
     $combinations =  $this->gerarCombinacoes( $arrayAttr, $this->coresSelecionadas);
  
        // Retorna os dados formatados para a requisição
        return [
            'name' => $this->nomeProduto . " " . $this->brand, // Nome do produto
            'descricao' => $this->description, // Descrição do produto
            'categoria' => $jsonCategories, // Categorias selecionadas
            'attributes' => $arrayAttr, // Atributos formatados
            'cores' => $this->coresSelecionadas // Cores selecionadas (possivelmente NULL)
        ];
    }
    

    public function gerarCombinacoes($attributes, $cores)
    {
        $combinacoes = [];
    
        // Fixando o ID da cor como 14
        $idCor = env("COR_ID");
        
        // Percorre todos os tamanhos (attributes)
        foreach ($attributes as $idTamanho => $tamanhos) {
            // Adiciona o array de combinações para o tamanho atual
            foreach ($tamanhos as $tamanho) {
                // Cria as combinações entre o tamanho e as cores
                foreach ($cores[$idCor] as $cor) {
                    // Adiciona a combinação com o ID do tamanho como chave
                    if (!isset($combinacoes[$idTamanho])) {
                        $combinacoes[$idTamanho] = [];
                    }
                    $combinacoes[$idTamanho][] = [$tamanho, $cor];
                }
            }
        }
    
        // Usando dd() para exibir as combinações
        dd($combinacoes);
    }
    
    

    

    public function generateProducts()
    {
        $request_data = $this->getRequestData();
       // $combinations = $this->generateCombinations($request_data['attributes']);
        
        dd($request_data);
    }
}