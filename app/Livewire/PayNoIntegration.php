<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Sell;

class PayNoIntegration extends Component
{
    public $sell;
    public $payment;
    public $total;
    public $parcelas;
    public $paymentmethod;
    public $showParcelas = false;
    public $totalReference;

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
      
    
        if ($this->total < $paymentValue) {
            
            
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

                
             $this->total = $this->total - $paymentValue;
           
             Payment::create($data);
           
        }
        // Gera um novo ID para a venda e recupera o CPF do usuário logado
     
      

        // Redirecionar ou mostrar uma mensagem de sucesso aqui, se necessário


    }
    
    public function endPurchase(){
        if($this->total == 0.0){
           $vendaAtual = Sell::find($this->sell['IdVenda']);
           $vendaAtual->cancelado = 0;
        
           // Salva as alterações no banco de dados
           $vendaAtual->save();
           session()->flash('mensagem', 'Venda Feita com sucesso');

           // Redireciona para a view desejada (exemplo: 'vendas.index')
           return redirect()->route('menu');
         }
         else{
            $this->dispatch('endPurchaseAlert');
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
private function getTotal($array){
    
    $total = 0;
    foreach ($array as $item) {
        $total += $item['product_real_qtde'];
        }
        return (float)$total;
}



    public function render()
    {
        return view('livewire.pay-no-integration');
    }
}