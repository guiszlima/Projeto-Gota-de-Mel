<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class Barcode extends Component
{   
    
    
    public $searchTerm;
    public $isSearch = False;
    public $products = [];
    public $currentPage = 1;
    public $totalPages;
    public $nmbrPerPage = 30;
    public  $searchBySku = false;




    public function render()
    {
        return view('livewire.barcode',
            [
                'products' => $this->products,
                'currentPage' => $this->currentPage,
                'totalPages' => $this->totalPages,
            ]);
    
        
    }

    

    public function mount(Client $woocommerce)
    {
        $this->loadProducts($woocommerce);
    }
    public function toggleSearchType()
    {
        $this->searchBySku = !$this->searchBySku;
    }
public function search(Client $woocommerce){
    $this->isSearch = true;
    $this->currentPage = 1; // Reinicia a página ao fazer nova pesquisa
    $this->loadProducts($woocommerce);
}
public function offSearch(Client $woocommerce){
    $this->isSearch = false;
    $this->currentPage = 1; // Reinicia a página ao fazer nova pesquisa
    $this->loadProducts($woocommerce);
}
    public function loadProducts(Client $woocommerce)
    {
        // Definindo parâmetros de paginação
        $params = [
            'per_page' => $this->nmbrPerPage,
            'page' => $this->currentPage,
            'orderby' => 'date', 
            'order' => 'desc',
            '_fields' => 'id,name,sku,price,stock_quantity,type',
            '_embed' => 'false'   

            
        ];
        if ($this->isSearch) {
            if ($this->searchBySku) {
                $params['sku'] = $this->searchTerm; // Busca por SKU
            } else {
                $params['search'] = $this->searchTerm; // Busca por Nome
            }
        }

        // Buscando produtos com os parâmetros definidos

        $this->products = $woocommerce->get('products', $params);
     
        if($this->searchBySku){
            foreach ($this->products as &$product) {
                if ($product->type === 'variation') {
                    // Se o produto for uma variação, substituímos o ID pelo parent_id
                    $product->id = $product->parent_id;
                }
            }
        }

        // Obter o total de produtos a partir dos cabeçalhos de resposta
        $responseHeaders = $woocommerce->http->getResponse()->getHeaders();
        
        try {
            $totalProducts = $responseHeaders['X-WP-Total'];
         } catch (\Throwable $th) {
            $totalProducts = $responseHeaders['x-wp-total'];
         }

        // Calculando o número total de páginas
        $this->totalPages = ceil($totalProducts / $this->nmbrPerPage);
    }


    public function goToPage($page, Client $woocommerce)
{
    if ($page >= 1 && $page <= $this->totalPages) {
        $this->currentPage = $page;
        $this->loadProducts($woocommerce);
    }
}

    public function nextPage(Client $woocommerce)
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
            $this->loadProducts($woocommerce);
        }
    }

    public function previousPage(Client $woocommerce)
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadProducts($woocommerce);
        }
    }



}
