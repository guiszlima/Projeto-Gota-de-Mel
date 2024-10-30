<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\ItensSell;

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

   


    public function payment(Request $request)
    {
        $sell = $request->all();
        dd($sell);
        // Decodifica o JSON do carrinho e calcula o total
        $cartItems = json_decode($sell['cart'], true);
        $total = $this->getTotal($cartItems);
    
        $userId = auth()->id();
    
        // Cria um registro de venda com o ID do usuário e o preço total
        $nowSell = Sell::create([
            'user_id' => $userId,
            'preco_total' => $total,
        ]);
    
        // Insere cada item do carrinho na tabela ItensSell
        foreach ($cartItems as $item) {
            ItensSell::create([
                'product_id' => $item['id'],
                'sell_id' => $nowSell->id,
                'nome' => $item['nome'], // Supondo que o campo 'nome' represente o nome do produto
            ]);
        }
    }
    
    private function getTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['product_real_qtde'];
        }
        return (float) $total;
    }
    
}