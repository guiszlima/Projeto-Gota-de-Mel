<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sell;
use App\Models\ReportCreate;
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
    public $estoque;
    public $estante;
    public $prateleira;
    
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
    $itemsQuery = Sell::query()
        ->join('users', 'sells.user_id', '=', 'users.id') // Join com a tabela 'users'
        ->join('payments', 'sells.id', '=', 'payments.sell_id') // Join com a tabela 'payments'
        ->join('itens_sells', 'sells.id', '=', 'itens_sells.sell_id') // Join com a tabela 'itens_sells'
        ->when($this->searchCPF, function($query) {
            $query->where('users.CPF', $this->searchCPF); // Filtra pelo CPF na tabela 'users'
        })
        ->when($this->selectedPay, function($query) {
            $query->where('payments.pagamento', $this->selectedPay); // Filtra pelo tipo de pagamento na tabela 'payments'
        })
        ->when($this->searchName, function($query) {
            $query->where('users.name', 'like', '%' . $this->searchName . '%'); // Filtra pelo nome do usuário
        })
        ->when($this->searchId, function($query) {
            $query->where('itens_sells.product_id', $this->searchId); // Filtra pelo ID do produto
        })
        ->when($this->searchPrice, function($query) {
            $query->where('payments.preco', $this->searchPrice); // Filtra pelo preço na tabela 'payments'
        })
        ->when($this->searchStartDate && $this->searchEndDate, function($query) {
            $query->whereBetween('sells.created_at', [$this->searchStartDate, $this->searchEndDate]); // Filtra pela data de criação
        })
        ->orderBy('sells.created_at', 'desc')
        ->select('sells.*', 'users.name as user_name', 'payments.pagamento', 'payments.preco', 'itens_sells.product_id'); // Incluído 'payments.preco'

    // Aplicar paginação
    $itemsPaginate = $itemsQuery->paginate(50);
    
    // Calcular o total de preços
    $totalPreco = $itemsQuery->sum('payments.preco'); // Calcular a soma antes da paginação

    return view('livewire.report-view-sells', ['items' => $itemsPaginate, 'soma' => $totalPreco]);

    }
}