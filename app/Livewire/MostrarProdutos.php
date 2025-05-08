<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Produtos; // Corrigido o namespace
use App\Models\ProdutoMeta;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Illuminate\Support\Str;



class MostrarProdutos extends Component
{



    
    public $searchTerm;
    public $products = [];
    public $contagem = [];
    public $cart = [];
    public $c = 0;
    public $uniqueId;
    public $formType = false;
    public $x = 0;
    public $perPage = 15; // Produtos por página

public function mount(){
$this->uniqueId  = Str::uuid()->toString();
}
public function changeFormtype(){
    
    $this->formType = !$this->formType;
    
}
 public function fetchProducts(Client $woocommerce)
    {
        if ($this->formType === false) {
            $param = [
                'sku' => $this->searchTerm,

            ];
           
            try {
                $pedido = Produtos::listarProdutoPorSku($this->searchTerm);
                
            } catch (\Exception $e) {
                
                Log::error('Erro ao buscar produtos no WooCommerce:', [
                    'mensagem' => $th->getMessage(),
                    'arquivo' => $th->getFile(),
                    'linha' => $th->getLine(),
                    'trace' => $th->getTraceAsString(),
                    'parametros' => $param, // Loga também os parâmetros se for útil
                ]);
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Erro com o servidor, favor tente novamente.'
                ]);
                return;
            }
          
            
            if ($pedido->isNotEmpty()) {
                $produto = $pedido[0];
        
                $this->addToCart(
                    $produto->name,
                    $produto->price,
                    $produto->id,
                    $produto->stock_quantity
                );
            }
        }
    else{
        
        $this->products = Produtos::listarProdutoPorSku($this->searchTerm);
        
        if ($this->products->isEmpty()) {
            $this->products = Produtos::listProductByName($this->searchTerm);
            
        }if ($this->products->isEmpty()) {
            $this->products = Produtos::listProductById($this->searchTerm);
            
        }
        if ($this->products->isEmpty()) {
            session()->flash('alert', [
                'type' => 'alert',
                'message' => 'Produto não encontrado.'
            ]);
        }
        foreach ($this->products as $product) {
            
            $this->contagem[$product->id] = 1;
        }

    }
    }        
    
    public function nextPage(Client $woocommerce)
{
    $this->page++;
    $this->fetchProducts($woocommerce);
}

public function previousPage(Client $woocommerce)
{
    if ($this->page > 1) {
        $this->page--;
        $this->fetchProducts($woocommerce);
    }
}
    public function addToCart($productName, $productValue,$productId,$prodStockQuantity){
        // Inicializa uma variável para indicar se o produto já foi encontrado
        
      $test = array_key_exists($productId, $this->cart);
    if($prodStockQuantity == 0){
        session()->flash('alert', [
            'type' => 'warning',
            'message' => 'O produto requisitado está com Estoque Zerado, favor atualizar o estoque antes de realizar a venda'
        ]);
        return;
    }
    if (!$test) {
            $this->cart[$productId]=  [
            'id' => $productId,
            'name' => $productName,
            'value' =>(float) $productValue,
            'quantidade' => 1,
            'stock'=> $prodStockQuantity,
            'product_real_qtde' => (float)($productValue * 1),             
        ];
      
        return;
       
    }
    else{
        
    if($this->cart[$productId]['stock'] > $this->cart[$productId]['quantidade']){
    $this->cart[$productId]['quantidade']++;
    $this->cart[$productId]['product_real_qtde'] =  $this->cart[$productId]['value'] * $this->cart[$productId]['quantidade'] ;
    }
    return;
    }  
    }
    public function increment($id){
        // dd($this->cart,$id);
        
        foreach( $this->cart as $key=> &$item) {
        
            if($item['id'] == $id && $item['stock'] > $item['quantidade']){
                
                $item['quantidade'] ++;
                $item['product_real_qtde']  = $item['value'] * $item['quantidade'] ;
                
                
                
            }    
            
    }
 
    

}
    public function decrement($id){
        

        
        foreach( $this->cart as $key => &$item ) {
            
            if($item['id'] == $id){
                $item['quantidade']--;
                $item['product_real_qtde']  = $item['value'] * $item['quantidade'];
            if($item['quantidade'] < 1 ){
                unset($this->cart[$key]);
            }
                break;
                
            }
        }
            
    
    }
    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['value'] * $item['quantidade'];
        });
    }
    




    public function render()
    {
        $data = [
            'quantidade' => $this->contagem,
            'products' => $this->products,
            'formState' => $this->formType,  
        ];
    
        return view('livewire.mostrar-produtos', compact("data"));
    }
}