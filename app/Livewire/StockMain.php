<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
use App\Models\Produtos;
use Livewire\WithPagination;
class StockMain extends Component
{
    public $searchTerm;
    public $isSearch = False;
    
    public $currentPage = 1;
    public $totalPages;
    public $nmbrPerPage = 15;
    public $searchBySku = false;
    public $params;
    public $searchType;
   
    public function toggleSearchType()
    {
        $this->searchBySku = !$this->searchBySku;
    }

    public function mount()
    {
        $this->loadProducts();
    }

public function search(Client $woocommerce){
    $this->isSearch = true;
    
    $this->loadProducts($woocommerce);
}
public function offSearch(Client $woocommerce){
    $this->isSearch = false;
    $this->loadProducts($woocommerce);
}
    public function loadProducts()
    {
        // Definindo parâmetros de paginação
        
        if ($this->isSearch) {
            $this->params = $this->searchTerm;
            if ($this->searchBySku) {
                
                $this->searchType = 'sku';
            } else {
               
                $this->searchType = 'name';
            }
        }
    }

    public function render()
    {
        $products = Produtos::listProducts($this->searchType, $this->params, $this->nmbrPerPage, true);
        return view('livewire.stock-main', [
            'products' => $products
        ]);
    }
}