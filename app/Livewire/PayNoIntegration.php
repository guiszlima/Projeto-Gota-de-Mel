<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Sell;
use Illuminate\Support\Facades\Http; // Adicione esta linha
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Automattic\WooCommerce\Client;

class PayNoIntegration extends Component
{
    public $sell;
    public $payment;
    public $total;
    public $parcelas;
    public $paymentmethod;
    public $showParcelas = false;
    public $troco = false;
    public $totalReference;
    public $paymentReference;
    public $dados;

    // Método para inicializar o componente com o valor de sell
    public function mount($sell)
    {
        // Decodifica o JSON do cart para um array
     
        
        $this->sell = $sell;
        $this->sell['cart'] =  json_decode($sell['cart'], true);
        $this->total = $this->getTotal($this->sell['cart']);
        $this->paymentmethod = $sell['payment_method'];
     

     
  
         return view('payment')->with('sell',$sell);
      
        
    }

    public function selling()
    {

        if(!$this->payment|| $this->payment === '0,00'){
            
            $this->dispatch('noPayment');
            return;
        }
        // Depuração dos tipos e valores
        // Limpar o valor de pagamento
        $cleanPayment = str_replace(['.', ' '], '', $this->payment); // Remove pontos e espaços
        $cleanPayment = str_replace(',', '.', $cleanPayment); // Troca vírgula por ponto
        // Converte para float
        $paymentValue = (float)$cleanPayment;
        // Debug para verificar o valor convertido
        // Verifica se o pagamento é maior que o valor total
      
    if ($this->paymentmethod === "dinheiro" && $this->total < $paymentValue && !$this->troco){
        $this->troco = $paymentValue - $this->total;
        $data = [
            'sell_id'=>$this->sell['IdVenda'],
            'pagamento'=> $this->paymentmethod,
            'preco'=> $paymentValue,
            'troco' =>  $this->troco,
        ];

        $this->paymentReference[] = $data;    

         $this->total = 0.0;
      
         Payment::create($data);
    }
    elseif ($this->total < $paymentValue && $this->troco ) {
            
            
        $this->dispatch('hasTroco');
        return;
    }
        elseif ($this->total < $paymentValue) {
            
            
            $this->dispatch('paymentTooHigh');
            return;
        }
        else{
          
            $data = [
                'sell_id'=>$this->sell['IdVenda'],
                'pagamento'=> $this->paymentmethod,
                'preco'=> $paymentValue,
                'parcelas'=> $this->parcelas
            ];

            $this->paymentReference[] = $data;
             $this->total = $this->total - $paymentValue;
            
             Payment::create($data);
           
        }
        // Gera um novo ID para a venda e recupera o CPF do usuário logado
     
      

        // Redirecionar ou mostrar uma mensagem de sucesso aqui, se necessário


    }
    
    public function endPurchase(Client $woocommerce)
    {
        if ($this->total == 0.0) {
         

            try { 
            $vendaAtual = Sell::find($this->sell['IdVenda']);
            $vendaAtual->cancelado = 0;

            // Salva as alterações no banco de dados
            $vendaAtual->save();
          
                $data = [];

                foreach ($this->sell['cart'] as $product) {
                    $newStockQuantity = $product['stock'] - $product['quantidade'];
                    $data[] = [
                        'id' => $product['id'],       // ID do produto
                        'stock_quantity' => $newStockQuantity, // Nova quantidade no estoque
                    ];
                }

                // Faz a solicitação em lote para atualizar o estoque
                $woocommerce->post('products/batch', [
                    'update' => $data,
                ]);

                session()->flash('mensagem', 'Venda Feita com sucesso');
            } catch (\Exception $e) {
                session()->flash('mensagem', 'Erro na venda favor contatar o desenvolvedor responsável');
            }

            // Redireciona para a view desejada (exemplo: 'vendas.index')
            return redirect()->route('menu');
        } else {
            $this->dispatch('endPurchaseAlert');
        }
    }

    public function printNota()
    {
        if ($this->total == 0.0) {
            // Dados necessários para gerar o PDF
            $this->dados = [
                'cart' => $this->sell['cart'],
                'paymentReference' => $this->paymentReference,
                'troco' => $this->troco,
            ];
    
            // Cria a URL da rota para gerar o PDF
            $url = route('generate.pdf') . '?' . http_build_query($this->dados);
    
            // Dispara o evento para o navegador abrir ou imprimir o PDF
            $this->dispatch('renderizar-pdf', ['url' => $url]);
        } else {
            $this->dispatch('printNotaAlert');
        }
    }
public function updatedPaymentmethod()
{
    
    $this->showParcelas = ($this->paymentmethod === 'credito');
    
}
    public function cancel_sell()
    {
        // Implementação de cancelamento de venda (se necessário)
    }
    private function getTotal($array)
    {
        $total = 0;
    
        foreach ($array as $item) {
            $total += $item['product_real_qtde'];
        }
    
     return floor($total * 100) / 100;; // Garante precisão de 2 casas decimais
    }


    public function render()
    {
        return view('livewire.pay-no-integration');
    }
}