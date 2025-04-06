<?php

namespace App\Livewire;

use Livewire\Component;
use Automattic\WooCommerce\Client;
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
public $perPage = 100; // Produtos por página

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
                'fields' => 'id,name,price,stock_quantity' // 🔥 Apenas os campos necessários
            ];
        
            $pedido = $woocommerce->get('products', $param);
        
            if (!empty($pedido)) {
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
        $this->products = $woocommerce->get('products', [
            'search' => $this->searchTerm,
            'per_page' => $this->perPage,
            
        ]);
        
        if (empty($this->products)) {
            $this->products = $woocommerce->get('products', [
                'sku' => $this->searchTerm,
              
                
            ]);
      
        }
        $productsWithVariations = [];
        $productIdsToRemove = [];
        
        foreach ($this->products as $product) {
            // Adiciona o produto principal com apenas os dados necessários
            $productsWithVariations[] = (object) [
                'id' => $product->id,
                'name' => $product->name,
                'stock_quantity' => $product->stock_quantity,
                'price' => $product->price,
                'parent_name' => null,
                'parent_id' => null
            ];
        
            $this->contagem[$product->id] = 1;
        
            if ($product->type === 'variable') {
                $variations = $woocommerce->get("products/{$product->id}/variations", [
                    'fields' => 'id,name,stock_quantity,price'
                ]);
        
                foreach ($variations as $variation) {
                    $productIdsToRemove[] = $product->id; // Marca o produto principal para remoção
        
                    $productsWithVariations[] = (object) [
                        'id' => $variation->id,
                        'name' => $product->name . " " . $variation->name, // Concatena com o nome do pai
                        'stock_quantity' => $variation->stock_quantity,
                        'price' => $variation->price,
                        'parent_name' => $product->name,
                        'parent_id' => $product->id
                    ];
        
                    $this->contagem[$variation->id] = 1;
                }
            }
        }
        
        // Filtra os produtos removendo os principais que possuem variações
        $this->products = array_filter($productsWithVariations, function ($product) use ($productIdsToRemove) {
            return !in_array($product->id, $productIdsToRemove);
        });
        

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