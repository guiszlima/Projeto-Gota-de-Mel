<?php

namespace App\Livewire;

use Livewire\Component;

class PayNoIntegration extends Component
{

    public $sell;
    public function render()
    {
        return view('livewire.pay-no-integration');
    }

public function sell(){

        //     aqui deve ser ajustado para a cada pagamento ser adicionado ao registro 
        //     $sellId = ReportSell::max('sell_id') + 1;
        //     $cpf = auth()->user()->CPF;
        //     foreach ($cartItems as $item){
        //        $test =  ReportSell::create([
        //         'sell_id' => $sellId,  
        //         'product_id' => $item['id'],
        //         'nome' => $item['name'], // Nome da variação do produto
        //         'CPF'=> $cpf,
        //         'preco'=>$item['real_qtde'],
        //         'quantidade'=>$item['quantidade'],
        //         'pagamento' => $sell['payment_method'] // Método de pagamento
        //         ]
        //     
        //         );
    
}

    public function mount($sell): void{
        $this->sell = $sell;
    }
}