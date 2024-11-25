<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use App\Models\Sell;
use App\Models\ReportCreate;

class ReportViewSells extends Component
{
    use WithPagination;

    protected $paginationTheme = 'pagination-theme';
    public $selectedPay;
    public $selectedStatus;
    public $searchName;
    public $searchId;
    public $searchSellId;
    public $searchPrice;
    public $searchStartDate;
    public $searchCPF;
    public $searchEndDate;
    public $estoque;
    public $estante;
    public $prateleira;
    public $searchPayment;
    public $searchTroco;
    public function applyFilters()
    {
        $this->resetPage(); // Reseta a página para 1 ao aplicar filtros
    }

    public function clearSelection()
    {
        $this->selectedStatus = null;
        $this->selectedPay = null; // Limpa a seleção
    }


    public function render()
    {

    // Aplicar os filtros e paginação
   $itemsQuery = Sell::query()
        ->join('users', 'sells.user_id', '=', 'users.id') // Join com a tabela 'users'
        ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
       
        ->when($this->searchCPF, function($query) {
            $query->where('users.CPF', 'like', '%' . $this->searchCPF . '%'); // Filtra pelo CPF na tabela 'users'
        })
        ->when($this->selectedPay, function($query) {
            $query->where('payments.pagamento', $this->selectedPay); // Filtra pelo tipo de pagamento na tabela 'payments'
        })
        ->when($this->selectedStatus !== null, function($query) {
            $query->where('sells.cancelado', (int)$this->selectedStatus); // Filtra pelo tipo de pagamento na tabela 'payments'
        })
        ->when($this->searchName, function($query) {
            $query->where('users.name', 'like', '%' . $this->searchName . '%'); // Filtra pelo nome do usuário
        })
        ->when($this->searchId, function($query) {
            $query->whereRaw('JSON_CONTAINS(sells.produtos, ?)', [json_encode($this->searchId)]); // Filtra pelo ID do produto no JSON
        })
        ->when($this->selectedPay, function($query) {
            $query->where('payments.pagamento', $this->selectedPay); // Filtra pelo tipo de pagamento na tabela 'payments'
        })
        ->when($this->searchSellId, function($query){
            $query->where('sells.id', 'like', '%' . $this->searchSellId); // Filtra pelo preço na tabela 'payments'
        })
        ->when($this->searchPrice, function($query) {
            $query->where('sells.preco_total', $this->searchPrice); // Filtra pelo preço na tabela 'sells'
        }) 
        ->when($this->searchTroco, function($query) {
            $query->where('payments.troco', $this->searchTroco); // Filtro correto no campo troco
        })
        ->when($this->searchStartDate && $this->searchEndDate, function($query) {
            $query->whereBetween('sells.created_at', [$this->searchStartDate, $this->searchEndDate]); // Filtra pela data de criação
        })
        ->orderBy('sells.created_at', 'desc')
        ->select('sells.*', 'users.name as user_name','users.CPF as user_cpf', 'payments.pagamento', 'payments.preco','payments.preco', 'payments.troco');
        

    // Aplicar paginação
    $itemsPaginate = $itemsQuery->paginate(50);
    
    // Calcular o total de preços
    $totalPreco = $itemsQuery->sum('payments.preco'); // Calcular a soma antes da paginação

    return view('livewire.report-view-sells', ['items' => $itemsPaginate, 'soma' => $totalPreco]);

    }
}