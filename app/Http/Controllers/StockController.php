<?php

namespace App\Http\Controllers;

use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
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
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,Client $woocommerce)
    {
        $currentRoute = Route::currentRouteName();
       return view('stock.create.create')->with('currentRoute',$currentRoute);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }



    public function createAttribute(Request $request,Client $woocommerce){
        $currentRoute = Route::currentRouteName();
            return view("stock.create.create-attribute")->with('currentRoute',$currentRoute);
            
    }
    public function storeAttribute(Request $request)
{
    dd($request->all());
}


    /**
     * Display the specified resource.
     */
    public function show(Client $woocommerce, string $id)
    {
        $product = $woocommerce->get("products/{$id}");
        if ($product->variations) {
            $variations = $woocommerce->get("products/{$id}/variations");
            
            foreach ($variations as $variation) {
                $variation->parent_name = $product->name;
                $variation->name = $variation->parent_name . " " . $variation->name;
                $variation->parent_id = $product->id;

                if ($variation->image) {
                    // Faça algo com a imagem, se necessário
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
    public function update(Request $request, Client $woocommerce)
{


    
    $data = $request->all();

    if (isset($data['variant_name'])) {
        $variantData = [];
        $variantAttr = [];
        $count = count($data['variant_name']);

        for ($i = 0; $i < $count; $i++) {
            $variantData[] = [
                'id' => $data['id'][$i],
                'name' => $data['variant_name'][$i],
                'sku' => $data['variant_sku'][$i],
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
                return response()->json([
                    'message' => 'Variações atualizadas com sucesso!',
                    'data' => $response->update, // Acessa os dados da atualização
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar variações.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    return response()->json([
        'message' => 'Nenhuma variação encontrada para atualizar.',
    ]);
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