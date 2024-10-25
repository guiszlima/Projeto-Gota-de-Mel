<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ReportSell;
use Illuminate\Support\Facades\Log;

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
    }

    public function selling()
    {
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
        $sellId = ReportSell::max('sell_id') + 1;
        $cpf = auth()->user()->CPF;
    
        // Insere cada item no banco de dados
        // ...
    }
    
 // // Insere cada item no banco de dados
        // foreach ($this->sell['cart'] as $item) {
        //     ReportSell::create([
        //         'sell_id' => $sellId,
        //         'product_id' => $item['id'],
        //         'nome' => $item['name'], // Nome do produto
        //         'CPF' => $cpf,
        //         'preco' => $item['real_qtde'], // Preço total
        //         'quantidade' => $item['quantidade'],
        //         'pagamento' => $this->sell['payment_method'] // Método de pagamento
        //     ]);
        // }

        // Redirecionar ou mostrar uma mensagem de sucesso aqui, se necessário


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
        return $total;
}
    public function render()
    {
        return view('livewire.pay-no-integration');
    }
}