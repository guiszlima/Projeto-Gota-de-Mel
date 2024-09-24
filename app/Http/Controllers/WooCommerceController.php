<?php

namespace App\Http\Controllers;

use App\Models\ReportSell;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{

   
    



    public function getProducts(Client $woocommerce)
    {
        try {
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function sellProducts(){
        


        return view("sell.sell-product");
    }

    public function payment(Request $request){
       
        $cpf = auth()->user()->CPF;
       $cartItems = json_decode($request->input('cart'), true);
        
      
            // Certifique-se de que a estrutura do array é consistente com os campos da tabela
            foreach ($cartItems as $item){
                ReportSell::create([
                'product_id' => $item['id'], // ID do produto
                'nome' => $item['name'], // Nome da variação do produto
                'CPF'=> $cpf,
                'quantidade'=>$item['quantidade'],
                'pagamento' => $item['value'], // Método de pagamento
                ]
                // Adicione outros campos se necessário
                );
         }
    
        dd($request->all());
    
}
}