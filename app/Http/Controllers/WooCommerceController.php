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
    
        // Debug: Mostra o conteúdo do array $sell
       
        
        if ($sell) {
            // Decodifica o JSON do carrinho e calcula o total
            $cartItems = json_decode($sell['cart'], true);
            $total = $this->getTotal($cartItems);
            $userId = auth()->id();
            $json_produtos = [];
            $json_quantidade = [];

            foreach ($cartItems as $item) {
                $json_produtos[] = $item['id'];
                $json_quantidade[] = $item['quantidade'];
            }
            $produtosJson = json_encode($json_produtos);
            $quantidadeJson = json_encode($json_quantidade);
            // Verifica se não há uma venda na sessão ou se o session_id não corresponde ao atual
            if (!session()->has('IdVenda') || session('session_id') !== $sell['session_id']) {
                // Cria um registro de venda com o ID do usuário e o preço total
                $nowSell = Sell::create([
                    'user_id' => $userId,
                    'preco_total' => $total,
                    'produtos' => $produtosJson,
                    'quantidade' =>$quantidadeJson,
                ]);
               
                // Insere cada item do carrinho na tabela ItensSell
              
                session(['IdVenda' => $nowSell->id, 'session_id' => $sell['session_id']]);
            } else {
                // Se já existir uma venda na sessão, recupera a venda existente
                $nowSell = Sell::find(session('IdVenda'));
            }
    
            // Adiciona o ID da venda à variável $sell
            $sell['IdVenda'] = $nowSell->id;
    
            // Retorna a view de pagamento com os dados da venda
            return view('payment')->with('sell', $sell);
        } else {
            // Retorna uma view se não houver registro de venda
            return view('no-order-registered');
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