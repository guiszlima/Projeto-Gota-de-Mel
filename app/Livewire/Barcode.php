<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produtos;

class Barcode extends Component
{
    public $searchTerm;
    public $isSearch = false;
    public $searchBySku = false;

    public $nmbrPerPage = 10;

    public function render()
    {
        $searchType = $this->searchBySku ? 'sku' : 'name';
        $searchParam = $this->isSearch ? $this->searchTerm : null;

        // Agora passando current_page da query string automaticamente
        $products = Produtos::listProducts($searchType, $searchParam, $this->nmbrPerPage, true);

        return view('livewire.barcode', [
            'products' => $products
        ]);
    }

    public function toggleSearchType()
    {
        $this->searchBySku = !$this->searchBySku;
    }

    public function search()
    {
        $this->isSearch = true;
    }

    public function offSearch()
    {
        $this->isSearch = false;
        $this->reset('searchTerm');
    }
}
