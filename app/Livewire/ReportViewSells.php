<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Automattic\WooCommerce\Client;
use App\Models\Sell;
use App\Models\ReportCreate;
use Illuminate\Support\Facades\Http;


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
    
    public $searchEndDate;
    public $estoque;
    public $estante;
    public $prateleira;
    public $searchPayment;
    public $searchTroco;
    public $searchDiscount;
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
        $this->itensTrocar = null;
    }

    public function trocaProduct(Client $woocommerce, $groupedItems)
    {
        $itens = $groupedItems[0];
        
        $venda = Sell::find($itens['id']);
        if ($venda->cancelado == 1) {
            $this->dispatch('alreadyCancelled');
            $this->dispatch('closeModal');
            return;
        }
        $createdAt = $venda->created_at; // Obtém a data de criação
        $currentDate = now(); // Obtém a data atual
        if ($createdAt->diffInDays($currentDate) > 7) {
            $this->dispatch('productTooOld');
            $this->dispatch('closeModal');
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
                'tipo' => $produto->type,
                'cont' => 0
            ];
            if($produto->type == 'variation'){
             $itensTrocar[$index]['parent_id'] = $produto->parent_id;
            }
        }

        // Atualiza a propriedade $itensTrocar
        $this->itensTrocar = $itensTrocar;
        $produtos = "";
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

    public function realizarTroca(Client $woocommerce, $id_venda, $id_produto, $cont, $preco, $quantidade, $tipo, $parent_id)
    {   


        
        if ($cont <= 0) {
            return response()->json(['error' => 'Quantidade de troca inválida'], 400);
        }
    
        // Marca a troca como realizada
        $this->isTrocado = true;
        $this->dispatch('troca-realizada');
    
        // Obtém a venda pelo ID
        $venda = Sell::find($id_venda);
        if (!$venda) {
            return response()->json(['error' => 'Venda não encontrada'], 404);
        }
    
        // Decodifica os produtos e quantidades armazenados como JSON
        $produtos = json_decode($venda->produtos, true) ?? [];
        $quantidades = json_decode($venda->quantidade, true) ?? [];
    
        // Calcula o valor total da troca
        $valorTotal = $cont * $preco;
    
        if ($tipo === 'variation') {
            // Busca as variações do produto pai
            $variacoes = $woocommerce->get("products/{$parent_id}/variations");
    
            foreach ($variacoes as $variacao) {
                if ($variacao->id == $id_produto) {
                    // Atualiza o estoque da variação específica
                    $woocommerce->put("products/{$parent_id}/variations/{$id_produto}", [
                        'stock_quantity' => max(0, $variacao->stock_quantity + $cont) // Evita estoque negativo
                    ]);
                    break;
                }
            }
        } else {
            // Obtém o produto no WooCommerce (produto simples)
            $produto = $woocommerce->get("products/{$id_produto}");
            if (!$produto) {
                return response()->json(['error' => 'Produto não encontrado'], 404);
            }
    
            // Atualiza o estoque do produto simples
            $woocommerce->put("products/{$id_produto}", [
                'stock_quantity' => max(0, $produto->stock_quantity + $cont) // Evita estoque negativo
            ]);
        }
    
        // Atualiza a quantidade do produto na venda
        foreach ($produtos as $key => $produtoId) {
            if ($produtoId == $id_produto && $quantidades[$key] > 0) {
                $quantidades[$key] = max(0, $quantidades[$key] - $cont); // Evita valores negativos
                $venda->preco_total = max(0, $venda->preco_total - $valorTotal); // Evita valores negativos
                break;
            }
        }
    
        // Reindexa os arrays para manter a estrutura correta
        $produtos = array_values($produtos);
        $quantidades = array_values($quantidades);
    
        // Atualiza a venda com os novos valores
        $venda->produtos = json_encode($produtos);
        $venda->quantidade = json_encode($quantidades);
        $venda->save();
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

    

    public function downloadPDF()
    {
        $data = [
            'selectedPay' => $this->selectedPay,
            'selectedStatus' => $this->selectedStatus,
            'searchName' => $this->searchName,
            'searchId' => $this->searchId,
            'searchSellId' => $this->searchSellId,
            'searchPrice' => $this->searchPrice,
            'searchTroco' => $this->searchTroco,
            'searchStartDate' => $this->searchStartDate  ,
            'searchEndDate' => $this->searchEndDate ,
        ];
        
        
        return redirect()->route('generate.pdf.relatorio', http_build_query($data));
    }
    
    
    
    public function render()
{
    // Substitui a vírgula por ponto e converte para float
    $searchPrice = floatval(str_replace(',', '.', $this->searchPrice));
    $searchPayment = floatval(str_replace(',', '.', $this->searchPayment));

    // Aplicar os filtros e paginação
    $itemsQuery = Sell::query()
        ->join('users', 'sells.user_id', '=', 'users.id')
        ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
        ->when($this->selectedPay, function ($query) {
            $query->where('payments.pagamento', $this->selectedPay); // Filtro tipo de pagamento
        })
        ->when($this->selectedStatus !== null, function ($query) {
            $query->where('sells.cancelado', (int)$this->selectedStatus); // Filtro status
        })
        ->when($this->searchName, function ($query) {
            $query->where('users.name', 'like', '%' . $this->searchName . '%'); // Filtro nome do usuário
        })
        ->when($this->searchId, function ($query) {
            $query->whereRaw('JSON_CONTAINS(sells.produtos, ?)', [json_encode($this->searchId)]); // Filtro por ID do produto
        })
        ->when($this->searchSellId, function ($query) {
            $query->where('sells.id', 'like', '%' . $this->searchSellId . '%'); // Filtro por ID da venda
        })
        ->when($searchPrice > 0, function ($query) use ($searchPrice) {
            $query->where('sells.preco_total', 'like', $searchPrice . '%'); // Filtro por preço total
        })
        ->when($searchPayment > 0, function ($query) use ($searchPayment) {
            $query->where('payments.preco', 'like', $searchPayment . '%'); // Filtro por preço de pagamento
        })
        ->when($this->searchTroco, function ($query) {
            $query->where('payments.troco', 'like', $this->searchTroco . '%'); // Filtro por troco
        })
        ->when($this->searchDiscount, function ($query) {
            $query->where('payments.desconto', $this->searchDiscount); // Filtro por desconto
        })
        ->when($this->searchStartDate || $this->searchEndDate, function ($query) {
            $startDate = ($this->searchStartDate ?? now()->toDateString()) . ' 00:00:00'; // Início do dia
            $endDate = ($this->searchEndDate ?? now()->toDateString()) . ' 23:59:59'; // Final do dia
        
            $query->whereBetween('sells.created_at', [$startDate, $endDate]);
        })
        ->orderBy('sells.created_at', 'desc')
        ->select('sells.*', 'users.name as user_name', 'payments.pagamento', 'payments.preco', 'payments.troco', 'payments.parcelas');

    // Aplicar paginação
    $itemsPaginate = $itemsQuery->paginate(50);

    // Calcular o total de preços antes da paginação
    $totalPreco = $itemsQuery->where('sells.cancelado', 0)->sum('sells.preco_total');

    return view('livewire.report-view-sells', ['items' => $itemsPaginate, 'soma' => $totalPreco]);
}

}