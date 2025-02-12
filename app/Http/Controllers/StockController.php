<?php

namespace App\Http\Controllers;

use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use App\WordpressClass;
use GuzzleHttp\Client as WpClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use phpDocumentor\Reflection\PseudoTypes\True_;
use App\Models\ReportCreate;
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
        try {
            $products = $woocommerce->get('products', $params);
        } catch (\Exception $e) {
            dd($e);
        }
        
        
        
        // Obter o total de produtos a partir dos cabeçalhos de resposta
        $responseHeaders = $woocommerce->http->getResponse()->getHeaders();
        
        $totalProducts = $responseHeaders['x-wp-total'];

        // Calculando o número total de páginas
        $totalPages = ceil($totalProducts / $nmbrPerPage);

        // Retornando a view com os produtos e informações de paginação
        return view("stock.stock-index", [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,Client $woocommerce)
    {
        $currentRoute = Route::currentRouteName();
        $categories_data = [];
        $woocategories = $woocommerce->get('products/categories');
        foreach($woocategories as $category){
        $categories_data[] = [
                        'id' =>  $category->id,
                        'name'=>$category->name
        ]; 
    }
       return view('stock.create.create')->with('currentRoute',$currentRoute)->with('categories', $categories_data)->with('currentRoute',$currentRoute);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Client $woocommerce, Request $request, WpClient $wpService )
{
    // Validação dos dados
  
}



      
    /**
     * Display the specified resource.
     */
    public function show(Client $woocommerce, string $id)
    {
        $product = $woocommerce->get("products/{$id}");
    
        if (!empty($product->variations)) {
            $variations = $woocommerce->get("products/{$id}/variations");
    
            foreach ($variations as $variation) {
                // Busca o relatório de criação baseado no ID da variação
                $repProd = ReportCreate::where('product_id', $variation->id)->first();
                
                $variation->parent_name = $product->name;
                $variation->name = $variation->parent_name . " " . $variation->name;
                $variation->parent_id = $product->id;
    
                // Verifica se repProd foi encontrado
             
                    $variation->estoque = $repProd->estoque;
                    $variation->estante = $repProd->estante;
                    $variation->prateleira = $repProd->prateleira;
             
    
            }
    
            $product = $variations;
        } else {
            $repProd = ReportCreate::where('product_id', $id)->first();
            $product->parent_id =$product->id;
            if ($repProd) {
                $product->estoque = $repProd->estoque;
                $product->estante = $repProd->estante;
                $product->prateleira = $repProd->prateleira;
            }
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
    public function update(Request $request, Client $woocommerce,WpClient $wpService)
{
    
    $data = $request->all();
   
    if($request->hasFile('image')){
        $wpClass = new WordpressClass($request->all(),$woocommerce,$wpService);
        $wpClass->EditarImagem();
    }
    if (isset($data['variant_name'])) {
        $variantData = [];
        $repItemUpdt = [];    
        $count = count($data['variant_name']);

        for ($i = 0; $i < $count; $i++) {
            $variantData[] = [
                'id' => $data['id'][$i],
                'name' => $data['variant_name'][$i],
                'sku' => $data['variant_sku'][$i],
                'regular_price' => $data['variant_price'][$i],
                'price' => $data['variant_price'][$i],
                'stock_quantity' => $data['variant_stock_quantity'][$i],
            ];
            
        }
        
        // Estrutura correta da requisição
        $req = [
            'update' => $variantData,
        ];
        
        try {
            // Chamada à API WooCommerce
            $response = $woocommerce->post("products/{$data['parent_id']}/variations/batch", $req);
          
            // Verifica se a resposta contém atualizações
            if (isset($response->update)) {
             
                foreach( $variantData as $index=> $var) {
                    $estoque = $data['estoque'][$index];
                    $estante = $data['estante'][$index];
                    $prateleira = $data['prateleira'][$index];
                    $updateReport=    ReportCreate::where('product_id', $var['id'])->update([
                        'nome' => $var['name'],
                        'preco' => $var['regular_price'],
                        'estoque' => $estoque,
                        'estante' => $estante,
                        'prateleira' => $prateleira,
                        ]);
                }
               
            }
            
            return back()->with('warn', 'Produto editado com sucesso' );
        } catch (\Exception $e) {
            return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsavel' );
        }
    }
    else{
        // Não é variação
    
        try{       
          $id =  $data['id'] ;
            $produto = [
            'name' => $data['name'],
             'regular_price' => $data['price'],
             'price' => $data['price'],
             'stock_quantity' => $data['quantity'],
             'sku'=> $data['sku'],
            ];
            $woocommerce->put("products/$id", $produto);
            $updateReport=    ReportCreate::where('product_id', $id)->update([
                'nome' => $data['name'],
                'preco' => $data['price'],
                'estoque' => $data['estoque'],
                'estante' => $data['estante'],
                'prateleira' => $data['prateleira']
                ]);

            return back()->with("warn", "Produto Editado com sucesso");
        }
        catch (\Exception $e) {
                       
            return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsavel' );
           
        }
        
    }
    


   
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