<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReportCreate;

class ReportView extends Component
{
    use WithPagination;

    protected $paginationTheme = 'pagination-theme';
    public $selectedType;
    public $searchName;
    public $searchId;
    public $searchPrice;
    public $searchStartDate;
    public $searchEndDate;
    public $estoque;
    public $estante;
    public $prateleira;
    
    
    
    
    public function applyFilters()
    {
        $this->resetPage(); // Reseta a página para 1 ao aplicar filtros
    }

    public function clearSelection()
    {
        $this->selectedType = null; // Limpa a seleção
    }


    public function render()
    {
        
        // Aplicar os filtros e paginação
        $items = ReportCreate::query()
            ->when($this->estoque, function($query) {
                $query->where('estoque', $this->estoque);
            })
            ->when($this->estante, function($query) {
                $query->where('estante', $this->estante);
            })
            ->when($this->prateleira, function($query) {
                $query->where('prateleira', $this->prateleira);
            })
            ->when($this->selectedType, function($query) {
                $query->where('type', $this->selectedType);
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
            ->when($this->searchStartDate || $this->searchEndDate, function ($query) {
                $startDate = ($this->searchStartDate ?? now()->toDateString()) . ' 00:00:00'; // Início do dia
                $endDate = ($this->searchEndDate ?? now()->toDateString()) . ' 23:59:59'; // Final do dia
            
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderBy('created_at','desc')
            ->paginate(30); // Aplicar paginação

        return view('livewire.report-view', ['items' => $items]);
    }
}