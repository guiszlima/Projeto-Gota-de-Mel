<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Automattic\WooCommerce\Client;
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
    public $isOpen = false; 
    public $selectedSellId; // ID da venda selecionada
    public $itensTrocar;
    public $cont;

    public function applyFilters()
    {
        $this->resetPage(); // Reseta a página para 1 ao aplicar filtros
    }

    public function clearSelection()
    {
        $this->selectedStatus = null;
        $this->selectedPay = null; // Limpa a seleção
    }

    public function openModal($id)
    {
        $this->selectedSellId = $id;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function trocaDeProduto()
    {
        $venda = Sell::find($this->selectedSellId);
        if ($venda->cancelado == 1) {
            $this->dispatch('alreadyCancelled');
        } else {
            $createdAt = $venda->created_at; // Obtém a data de criação
            $currentDate = now(); // Data e hora atual

            if ($createdAt->diffInDays($currentDate) > 7) {
                $this->dispatch('productTooOld');
            } else {
                $venda->cancelado = 1; // Marca a venda como cancelada
                $venda->save(); // Salva as alterações no banco de dados

                session()->flash('alert', [
                    'type' => 'success',
                    'message' => 'Venda cancelada, o produto pode ser trocado'
                ]);
                return redirect()->route('products.sell');
            }
        }
    }

    public function trocaProduct(Client $woocommerce, $groupedItems)
    {
        $itens = $groupedItems[0];

        // Decodifica as strings JSON dos produtos e quantidades
        $produtos = json_decode($itens['produtos']);
        $produtoQtde = json_decode($itens['quantidade']);
    
        // Inicializa o array para armazenar os itens que vamos trocar
        $itensTrocar = [];
    
        // Loop pelos produtos e monta o array com nome, preço, ID e quantidade
        foreach ($produtos as $index => $produtoId) {
            // Realiza a solicitação ao WooCommerce para obter o produto pelo ID
            $produto = $woocommerce->get('products/' . $produtoId);
    
            // Adiciona as informações do produto no array $itensTrocar
            $itensTrocar[] = [
                'venda_id' => $itens['id'],
                'nome_produto' => $produto->name,
                'preco_produto' => $produto->price,
                'id_produto' => $produto->id,
                'quantidade' => $produtoQtde[$index]
            ];
        }
    
        // Atualiza a propriedade $itensTrocar
        $this->itensTrocar = $itensTrocar;
    
        // Notifica o frontend que os dados estão prontos
        $this->dispatch('itens-troca-atualizados');
    }

    public function increment($id, $preco)
    {
      
        foreach( $this->itensTrocar as $key=> &$item) {
        
            if($item['venda_id'] == $id && $this->cont > $item['quantidade']){
                $this->cont +=1;
                
                
                
                
            }    
            
    }
    }
    

    public function decrement($quantidade, $preco)
    {
     
        foreach( $this->itensTrocar as $key => &$item ) {
            
            if($item['id'] == $id){
                $item['quantidade'] -=1;
            
                break;
                
            }
        }
     
    }
    public function realizarTroca(Client $woocommerce, $id_venda, $id_produto, $quantidade, $preco)
    {
        // Implementar a lógica de troca de produto
    }

    public function render()
    {
        $this->cont = 0;
        // Aplicar os filtros e paginação
        $itemsQuery = Sell::query()
            ->join('users', 'sells.user_id', '=', 'users.id') // Join com a tabela 'users'
            ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
            ->when($this->searchCPF, function ($query) {
                $query->where('users.CPF', 'like', '%' . $this->searchCPF . '%'); // Filtra pelo CPF na tabela 'users'
            })
            ->when($this->selectedPay, function ($query) {
                $query->where('payments.pagamento', $this->selectedPay); // Filtra pelo tipo de pagamento na tabela 'payments'
            })
            ->when($this->selectedStatus !== null, function ($query) {
                $query->where('sells.cancelado', (int)$this->selectedStatus); // Filtra pelo tipo de pagamento na tabela 'payments'
            })
            ->when($this->searchName, function ($query) {
                $query->where('users.name', 'like', '%' . $this->searchName . '%'); // Filtra pelo nome do usuário
            })
            ->when($this->searchId, function ($query) {
                $query->whereRaw('JSON_CONTAINS(sells.produtos, ?)', [json_encode($this->searchId)]); // Filtra pelo ID do produto no JSON
            })
            ->when($this->selectedPay, function ($query) {
                $query->where('payments.pagamento', $this->selectedPay); // Filtra pelo tipo de pagamento na tabela 'payments'
            })
            ->when($this->searchSellId, function ($query) {
                $query->where('sells.id', 'like', '%' . $this->searchSellId . '%'); // Filtra pelo preço na tabela 'payments'
            })
            ->when($this->searchPrice, function ($query) {
                $query->where('sells.preco_total', $this->searchPrice); // Filtra pelo preço na tabela 'sells'
            })
            ->when($this->searchTroco, function ($query) {
                $query->where('payments.troco', $this->searchTroco); // Filtro correto no campo troco
            })
            ->when($this->searchStartDate && $this->searchEndDate, function ($query) {
                $query->whereBetween('sells.created_at', [$this->searchStartDate, $this->searchEndDate]); // Filtra pela data de criação
            })
            ->orderBy('sells.created_at', 'desc')
            ->select('sells.*', 'users.name as user_name', 'users.CPF as user_cpf', 'payments.pagamento', 'payments.preco', 'payments.preco', 'payments.troco');

        // Aplicar paginação
        $itemsPaginate = $itemsQuery->paginate(50);

        // Calcular o total de preços
        $totalPreco = $itemsQuery->where('sells.cancelado', 0)->sum('sells.preco_total');  // Calcular a soma antes da paginação

        return view('livewire.report-view-sells', ['items' => $itemsPaginate, 'soma' => $totalPreco]);
    }
}