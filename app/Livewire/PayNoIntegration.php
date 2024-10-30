<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sell;
use App\Models\payments;
use App\Models\ItensSell;

class PayNoIntegration extends Component
{
    public $sell;
    public $payment;
    public $total;
    
    public $paymentmethod;
    public $showParcelas = false;

    // Método para inicializar o componente com o valor de sell
    public function mount($sell)
    {
        // Decodifica o JSON do cart para um array
     

        $this->sell = $sell;
        $this->sell['cart'] =  json_decode($sell['cart'], true);
        $this->total = $this->getTotal($this->sell['cart']);
        
        $userId = auth()->id();

        $nowSell =  Sell::create([

            'user_id' => $userId,
            'preco_total'=>  $this->total,

    ]);

    foreach($this->sell['cart'] as $item){

        ItensSell::create([
            'product_id'=> $item['id'],
            'sell_id' => $nowSell->id,
            'nome'=> $item['id'],

        ]);
    }
  
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
             $this->total = $this->total - $paymentValue;
             
        }
        // Gera um novo ID para a venda e recupera o CPF do usuário logado
     
      

        // Redirecionar ou mostrar uma mensagem de sucesso aqui, se necessário


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

private function createSell(){
    
}

    public function render()
    {
        return view('livewire.pay-no-integration');
    }
}