<?php

namespace App\Http\Controllers;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Client $woocommerce)
    {
       // Recuperando o número da página atual da requisição
       $page = $request->input('page', 1);
       $nmbrPerPage = 20;
       // Definindo parâmetros de paginação e filtros
       $params = [
          'per_page' => $nmbrPerPage, // Número de produtos por página
          'page' => $page, // Página atual
          
       ];
       
       // Buscando produtos com os parâmetros definidos
       $products = $woocommerce->get('products', $params);
       
       // Obter o total de produtos a partir dos cabeçalhos de resposta
       $responseHeaders = $woocommerce->http->getResponse()->getHeaders();
       $totalProducts = $responseHeaders['X-WP-Total'];
      
       // Calculando o número total de páginas
       $totalPages = ceil($totalProducts / $nmbrPerPage);
       // Retornando a view com os produtos e informações de paginação
       return view("stock.stock-index", [
          'products' => $products,
          'currentPage' => $page,
          'totalPages' => $totalPages
       ]);
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $woocommerce, string $id)
    {
        $product = $woocommerce->get("products/{$id}");
        if($product->variations){
            $variations = $woocommerce->get("products/{$id}/variations");
            
                foreach($variations as $variation){
                    $variation->parent_name = $product->name;
                    $variation ->name = $variation->parent_name ." ". $variation->name;
                    $variation->parent_id = $product->id;
                    if ($variation->image) {
                        # code...
                    }
                }
                $product = $variations;

       }
        
        return view("stock.stock-show")->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $woocommerce, string $id)
    {
         $woocommerce->delete("products/{$id}");
        return back()->with('success', 'Produto Deletado com Sucesso!');
    }
    
}