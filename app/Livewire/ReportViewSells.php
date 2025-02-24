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
    public $isTrocado = false;

    public function applyFilters()
    {
        $this->resetPage(); // Reseta a página para 1 ao aplicar filtros
    }

    public function clearSelection()
    {
        $this->selectedStatus = null;
        $this->selectedPay = null; // Limpa a seleção
    }

    public function fechaModal(){
        $this->isTrocado = false;
    }

    public function trocaProduct(Client $woocommerce, $groupedItems)
    {
        $itens = $groupedItems[0];

        $venda = Sell::find($itens['id']);
        if ($venda->cancelado == 1) {
            $this->dispatch('alreadyCancelled');
            return;
        }
        $createdAt = $venda->created_at; // Obtém a data de criação
        $currentDate = now(); // Obtém a data atual
        if ($createdAt->diffInDays($currentDate) > 7) {
            $this->dispatch('productTooOld');
            return;
        }
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
                'quantidade' => $produtoQtde[$index] ?? null,
                'cont' => 0
            ];
        }

        // Atualiza a propriedade $itensTrocar
        $this->itensTrocar = $itensTrocar;

        // Notifica o frontend que os dados estão prontos
        $this->dispatch('itens-troca-atualizados');
    }

    public function venderTroca()
    {
        if (!$this->isTrocado) {
            $this->dispatch('troca-nao-realizada');
            return;
        }
        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Produtos trocados, favor realizar a revenda se necessário'
        ]);
        return redirect()->route('products.sell');
    }

    public function increment($preco, $id_produto)
    {
        foreach ($this->itensTrocar as $key => &$item) {
            if ($item['id_produto'] == $id_produto && $item['quantidade'] > $item['cont']) {
                $item['cont'] += 1;
                break;
            }
        }
    }

    public function decrement($preco, $id_produto)
    {
        foreach ($this->itensTrocar as $key => &$item) {
            if ($item['id_produto'] == $id_produto && $item['cont'] > 0) {
                $item['cont'] -= 1;
                break;
            }
        }
    }

    public function realizarTroca(Client $woocommerce, $id_venda, $id_produto, $cont, $preco, $quantidade)
    {
        if ($quantidade != 0 || $cont != 0) {
            $this->isTrocado = true;
            $this->dispatch('troca-realizada');
        }

        // Obtém o produto no WooCommerce
        $produto = $woocommerce->get('products/' . $id_produto);

        // Calcula o valor total da troca
        $valorTotal = $cont * $preco;

        // Obtém a venda pelo ID
        $venda = Sell::find($id_venda);

        if (!$venda) {
            return response()->json(['error' => 'Venda não encontrada'], 404);
        }

        // Decodifica os produtos e quantidades armazenados como JSON
        $produtos = json_decode($venda->produtos, true) ?? [];
        $quantidades = json_decode($venda->quantidade, true) ?? [];

        // Atualiza o estoque no WooCommerce
        $woocommerce->put('products/' . $id_produto, [
            'stock_quantity' => $produto->stock_quantity + $cont
        ]);

        // Atualiza a quantidade do produto na venda
        foreach ($produtos as $key => $produtoId) {
            if ($produtoId == $id_produto && $quantidades[$key] > 0) {
                $quantidades[$key] -= $cont;
                $venda->preco_total -= $valorTotal;
                break;
            }
        }

        // Reindexa os arrays para garantir que os índices estejam corretos
        $produtos = array_values($produtos);
        $quantidades = array_values($quantidades);

        // Atualiza a venda com os novos valores
        $venda->produtos = json_encode($produtos);
        $venda->quantidade = json_encode($quantidades);
        $venda->save();

        // Notifica o frontend
    }

    public function imprimirNota()
    {
        if (!$this->isTrocado) {
            $this->dispatch('troca-nao-realizada');
            return;
        }
        $url = route('generate.pdf.troca') . '?' . http_build_query($this->itensTrocar);

        // Dispara o evento para o navegador abrir ou imprimir o PDF
        $this->dispatch('renderizar-pdf', ['url' => $url]);
    }

    public function downloadExcel()
    {
        // Fazendo a consulta para pegar as vendas do dia
        $sales = Sell::query()
            ->join('users', 'sells.user_id', '=', 'users.id')
            ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
            ->when($this->searchCPF, function ($query) {
                $query->where('users.CPF', 'like', '%' . $this->searchCPF . '%');
            })
            ->when($this->selectedPay, function ($query) {
                $query->where('payments.pagamento', $this->selectedPay);
            })
            ->when($this->selectedStatus !== null, function ($query) {
                $query->where('sells.cancelado', (int)$this->selectedStatus);
            })
            ->when($this->searchName, function ($query) {
                $query->where('users.name', 'like', '%' . $this->searchName . '%');
            })
            ->when($this->searchId, function ($query) {
                $query->whereRaw('JSON_CONTAINS(sells.produtos, ?)', [json_encode($this->searchId)]);
            })
            ->when($this->searchSellId, function ($query) {
                $query->where('sells.id', 'like', '%' . $this->searchSellId . '%');
            })
            ->when($this->searchPrice, function ($query) {
                $query->where('sells.preco_total', $this->searchPrice);
            })
            ->when($this->searchTroco, function ($query) {
                $query->where('payments.troco', $this->searchTroco);
            })
            ->when($this->searchStartDate && $this->searchEndDate, function ($query) {
                $query->whereBetween('sells.created_at', [$this->searchStartDate, $this->searchEndDate]);
            })
            // Adicionando a condição para filtrar vendas não canceladas
            ->where('sells.cancelado', false)
            ->whereDate('sells.created_at', today())
            ->orderBy('sells.created_at', 'desc')
            ->select('sells.*', 'users.name as user_name', 'users.CPF as user_cpf', 'payments.pagamento', 'payments.preco', 'payments.troco');
        
        // Convertendo as vendas para um array simples
        $salesData = $sales->get()->map(function ($sale) {
            
            return [
                'id' => $sale->id,
                'created_at' => $sale->created_at->format('d/m/Y H:i:s'),
                'user_name' => $sale->user_name,
                'user_cpf' => $sale->user_cpf,
                'pagamento' => $sale->pagamento,
                'preco' => 'R$' . number_format($sale->preco, 2, ',', '.'),
                'produtos' => '"' . implode('", "', json_decode($sale->produtos)) . '"',
                'status' => $sale->cancelado ? 'Cancelado' : 'Aprovado',
                'troco' => $sale->troco ? 'R$' . number_format($sale->troco, 2, ',', '.') : '',
            ];
        });
        
    
        // Disparando o evento JavaScript e passando os dados como array
        $this->dispatch('export-sales', ['sales' => $salesData]);
    }
    
    
    public function render()
    {
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