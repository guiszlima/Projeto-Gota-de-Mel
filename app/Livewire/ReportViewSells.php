<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReportSell;

class ReportViewSells extends Component
{
    use WithPagination;

    protected $paginationTheme = 'pagination-theme';
    public $selectedPay;
    public $searchName;
    public $searchId;
    public $searchPrice;
    public $searchStartDate;
    public $searchCPF;
    public $searchEndDate;
    
    
    public function applyFilters()
    {
        $this->resetPage(); // Reseta a página para 1 ao aplicar filtros
    }

    public function clearSelection()
    {
        $this->selectedPay = null; // Limpa a seleção
    }


    public function render()
    {
      
        // Aplicar os filtros e paginação
        $items = ReportSell::query()
        ->when($this->searchCPF, function($query){
            $query->where('CPF',$this->searchCPF);
        })
            ->when($this->selectedPay, function($query) {
                $query->where('pagamento', $this->selectedPay);
            })
            ->when($this->searchName, function($query) {
                $query->where('nome', 'like', '%' . $this->searchName . '%');
            })
            ->when($this->searchId, function($query) {
                $query->where('product_id', $this->searchId);
            })
            ->when($this->searchPrice, function($query) {
                $query->where('preco', $this->searchPrice);
            })
            ->when($this->searchStartDate && $this->searchEndDate, function($query) {
                $query->whereBetween('created_at', [$this->searchStartDate, $this->searchEndDate]);
            })
            ->orderBy('created_at','desc');
             // Aplicar paginação
             $totalPreco = $items->sum('preco');
             $itemsPaginate = $items->paginate(50);

        return view('livewire.report-view-sells', ['items' => $itemsPaginate,'soma' =>$totalPreco]);
    }
}