<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
class Barcode extends Component
{
    public function render()
    {
        return view('livewire.barcode',
            [
                'products' => $this->products,
                'currentPage' => $this->currentPage,
                'totalPages' => $this->totalPages,
            ]);
    
        
    }

    public $searchTerm;
    public $isSearch = False;
    public $products = [];
    public $currentPage = 1;
    public $totalPages;
    public $nmbrPerPage = 30;

    public function mount(Client $woocommerce)
    {
        $this->loadProducts($woocommerce);
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
            
        ];
        if($this->isSearch){
            $params['search'] = $this->searchTerm;
        }

        // Buscando produtos com os parâmetros definidos

        $this->products = $woocommerce->get('products', $params);
     
        

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
