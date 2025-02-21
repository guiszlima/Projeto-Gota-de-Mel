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
use App\Providers\WordpressServiceProvider;

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
    public function create(Request $request, Client $woocommerce)
    {
        $currentRoute = Route::currentRouteName();
        $categories_data = [];
        $woocategories = $woocommerce->get('products/categories');
        foreach ($woocategories as $category) {
            $categories_data[] = [
                'id' => $category->id,
                'name' => $category->name
            ];
        }
        return view('stock.create.create')->with('currentRoute', $currentRoute)->with('categories', $categories_data)->with('currentRoute', $currentRoute);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Client $woocommerce, Request $request, WpClient $wpService)
    {
        // Debug para verificar os dados recebidos
        //dd($request->all());

        // Verifica se o tipo de produto é "simple"
        if ($request->input('product_type') === 'simple') {
            return $this->postSimpleProduct($woocommerce, $request);
        } elseif ($request->input('product_type') === 'variable') {
            return $this->postVariableProduct($woocommerce, $request);
        }

        return response()->json(['message' => 'Tipo de produto não suportado'], 400);
    }

    private function postSimpleProduct(Client $woocommerce, Request $request)
    {
        // Converter o preço de "22,22" para "22.22"
        $preco = str_replace(',', '.', $request->input('preco')[0]);
        $preco = floatval($preco);

        $sku = strtoupper(substr($request->input('product_name'), 0, 3)) . '-' . time();

        // Verifica se a imagem foi enviada corretamente
        $image_id = null;
        if ($request->hasFile('imagem.0')) {
            $wpService = new WpClient();
            $imageFile = $request->file('imagem.0');
            $wordpressServiceProvider = new WordpressServiceProvider(app());
            $image_id = $wordpressServiceProvider->uploadWP($imageFile, $request->input('product_name'), $wpService);
        }

        $productData = [
            'name' => $request->input('product_name'),
            'type' => 'simple',
            'sku' => $sku,
            'regular_price' => (string) $preco, // WooCommerce exige string numérica
            'stock_quantity' => (int) $request->input('quantidade'),
            'categories' => [
                [
                    'id' => (int) $request->input('category'),
                ]
            ],
            'meta_data' => [
                [
                    'key' => 'estoque',
                    'value' => $request->input('estoque')[0] ?? '',
                ],
                [
                    'key' => 'estante',
                    'value' => $request->input('estante') ?? '',
                ],
                [
                    'key' => 'prateleira',
                    'value' => $request->input('prateleira') ?? '',
                ]
            ]
        ];

        if (!empty($image_id)) {
            $productData['images'] = [['id' => $image_id]];
        }

        try {
            // Envia o produto para o WooCommerce
            $response = $woocommerce->post('products', $productData);
            return response()->json(['message' => 'Produto criado com sucesso!', 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o produto', 'details' => $e->getMessage()], 500);
        }
    }

    private function postVariableProduct(Client $woocommerce, Request $request) { 
        // Criar os atributos do produto pai dinamicamente
        $attributes = [];
        foreach ($request->all() as $key => $values) {
            if (is_array($values) && is_numeric($key)) { // Verifica se é um atributo
                $attributes[] = [
                    'id' => (int) $key, // ID do atributo no WooCommerce
                    'name' => '', // Nome não é necessário, pois já temos o ID
                    'variation' => true,
                    'options' => array_unique($values) // Evita valores repetidos
                ];
            }
        }
    
        // Criar o produto variável (PAI)
        $productData = [
            'name' => $request->input('product_name'),
            'type' => 'variable',
            'description' => $request->input('description'),
            'categories' => [
                ['id' => (int) $request->input('category')]
            ],
            'attributes' => $attributes
        ];
    
        // Enviar para WooCommerce e criar produto PAI
        $productResponse = $woocommerce->post('products', $productData);
    
        // Criar variações do produto PAI
        if (!empty($productResponse->id)) {
            $this->createVariations($woocommerce, $request, $productResponse->id);
        }
    }
    
    private function createVariations(Client $woocommerce, Request $request, $product_id) {
        $variations = [];
    
        foreach ($request->input('preco') as $index => $preco) {
            $precoFormatado = str_replace(',', '.', $preco); // Convertendo "23,23" para "23.23"
    
            // Criar os atributos da variação dinamicamente
            $variationAttributes = [];
            foreach ($request->all() as $key => $values) {
                if (is_array($values) && is_numeric($key)) { // Verifica se é um atributo
                    $variationAttributes[] = [
                        'id' => (int) $key, // ID do atributo no WooCommerce
                        'option' => $values[$index] // Valor da variação
                    ];
                }
            }
    
            $variations[] = [
                'regular_price' => (string) floatval($precoFormatado),
                'stock_quantity' => (int) $request->input('quantidade')[$index],
                'attributes' => $variationAttributes,
                'meta_data' => [
                    ['key' => 'estoque', 'value' => $request->input('estoque')[$index] ?? ''],
                    ['key' => 'estante', 'value' => $request->input('estante')[$index] ?? ''],
                    ['key' => 'prateleira', 'value' => $request->input('prateleira')[$index] ?? '']
                ]
            ];
        }
    
        // Enviar todas as variações para o WooCommerce de uma vez
        $woocommerce->post("products/{$product_id}/variations/batch", [
            'create' => $variations
        ]);
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
                if ($repProd) {
                    $variation->estoque = $repProd->estoque;
                    $variation->estante = $repProd->estante;
                    $variation->prateleira = $repProd->prateleira;
                }
            }

            $product = $variations;
        } else {
            $repProd = ReportCreate::where('product_id', $id)->first();
            $product->parent_id = $product->id;
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
    public function update(Request $request, Client $woocommerce, WpClient $wpService)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $wpClass = new WordpressClass($request->all(), $woocommerce, $wpService);
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
                    foreach ($variantData as $index => $var) {
                        $estoque = $data['estoque'][$index];
                        $estante = $data['estante'][$index];
                        $prateleira = $data['prateleira'][$index];
                        ReportCreate::where('product_id', $var['id'])->update([
                            'nome' => $var['name'],
                            'preco' => $var['regular_price'],
                            'estoque' => $estoque,
                            'estante' => $estante,
                            'prateleira' => $prateleira,
                        ]);
                    }
                }

                return back()->with('warn', 'Produto editado com sucesso');
            } catch (\Exception $e) {
                return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsável');
            }
        } else {
            // Não é variação
            try {
                $id = $data['id'];
                $produto = [
                    'name' => $data['name'],
                    'regular_price' => $data['price'],
                    'price' => $data['price'],
                    'stock_quantity' => $data['quantity'],
                    'sku' => $data['sku'],
                ];
                $woocommerce->put("products/$id", $produto);
                ReportCreate::where('product_id', $id)->update([
                    'nome' => $data['name'],
                    'preco' => $data['price'],
                    'estoque' => $data['estoque'],
                    'estante' => $data['estante'],
                    'prateleira' => $data['prateleira']
                ]);

                return back()->with("warn", "Produto Editado com sucesso");
            } catch (\Exception $e) {
                return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsável');
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