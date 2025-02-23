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
        }

        // Obter o total de produtos a partir dos cabeçalhos de resposta
        $responseHeaders = $woocommerce->http->getResponse()->getHeaders();
        try {
            $totalProducts = $responseHeaders['X-WP-Total'];
         } catch (\Throwable $th) {
            $totalProducts = $responseHeaders['x-wp-total'];
         }

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
        return view('stock.create.create')->with('categories', $categories_data)->with('currentRoute', $currentRoute);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Client $woocommerce, Request $request, WpClient $wpService)
    {
        // Debug para verificar os dados recebidos
        

        // Verifica se o tipo de produto é "simple"
        if ($request->input('product_type') === 'simple') {
            $this->postSimpleProduct($woocommerce, $request);

            return view('stock.create.create');

        } elseif ($request->input('product_type') === 'variable') {
            $this->postVariableProduct($woocommerce, $request, $wpService);
            
            return view('stock.create.create');
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
        if ($request->hasFile('image.0')) {
            $wpService = new WpClient();
            $imageFile = $request->file('image.0');
            $wordpressServiceProvider = new WordpressServiceProvider(app());
            $image_id = $wordpressServiceProvider->uploadWP($imageFile, $request->input('product_name'), $wpService);
        }

        $productData = [
            'name' => $request->input('product_name'),
            'type' => 'simple',
            'sku' => $sku,
            'manage_stock' =>true,
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
                    'value' => $request->input('estante')[0] ?? '',
                ],
                [
                    'key' => 'prateleira',
                    'value' => $request->input('prateleira')[0] ?? '',
                ]
            ]
            
        ];

        if (!empty($image_id)) {
            $productData['images'] = [
                ['id' => $image_id]
            ];
        }
           
        try {
            // Envia o produto para o WooCommerce
            $response = $woocommerce->post('products', $productData);
            
            ReportCreate::create([
                'product_id' => $response->id,
                'nome' => $request->input('product_name'),
                'estoque'=> $request->input('estoque')[0],
                'estante' => $request->input('estante')[0],
                'prateleira'=>$request->input('prateleira')[0],
                'preco' => $response->regular_price,
                'type'=> 'simples'
            ]);
            
            return response()->json(['message' => 'Produto criado com sucesso!', 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o produto', 'details' => $e->getMessage()], 500);
         
        }
    }

    private function postVariableProduct(Client $woocommerce, Request $request, WpClient $wpService) { 
        // Criar os atributos do produto pai dinamicamente
        $attributes = [];
        foreach ($request->all() as $key => $values) {
            if (is_array($values) && is_numeric($key) ) { // Verifica se é um atributo
                $attributes[] = [
                    'id' => (int) $key, // ID do atributo no WooCommerce
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
            $this->createVariations($woocommerce, $request, $productResponse->id, $wpService);
        }
    }
    
    private function createVariations(Client $woocommerce, Request $request, $product_id, WpClient $wpService) {
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


            $sku = strtoupper(substr($request->input('product_name'), 0, 3)) . '-' . time() . '-' . $index;
           
            if ($request->hasFile("image.{$index}")) {
                $uploadedFile = $request->file("image.{$index}");
                if ($uploadedFile instanceof \Illuminate\Http\UploadedFile) {
                    $wordpressServiceProvider = new WordpressServiceProvider(app());
                    $imageIds[$index] = $wordpressServiceProvider->uploadWP($uploadedFile, $request->input('product_name'), $wpService);
                }
            }
            
            $variations[] = [
                'regular_price' => (string) floatval($precoFormatado),
                'sku' => $sku,
                'stock_quantity' => (string) $request->input('quantidade')[$index],
                'manage_stock' =>true,
                'attributes' => $variationAttributes,
                'meta_data' => [
                    ['key' => 'estoque', 'value' => $request->input('estoque')[$index] ?? ''],
                    ['key' => 'estante', 'value' => $request->input('estante')[$index] ?? ''],
                    ['key' => 'prateleira', 'value' => $request->input('prateleira')[$index] ?? '']
                ]
            ];
            if (!empty($imageIds[$index])) {
                $variations[$index]['image'] = ['id' => $imageIds[$index]];
            }
            

        
        
        }
    
        // Enviar todas as variações para o WooCommerce de uma vez
       $response =  $woocommerce->post("products/{$product_id}/variations/batch", [
            'create' => $variations
        ]);
        
        foreach ($response->create as $variation) {
            ReportCreate::create([
                'product_id' => $variation->id,
                'nome' => $request->input('product_name'),
                'estoque'=> $request->input('estoque')[$index],
                'estante' => $request->input('estante')[$index],
                'prateleira'=>$request->input('prateleira')[$index],
                'preco' => (string) $request->input('quantidade')[$index],
                'type'=> 'variable'
            ]);
        }
       

    }
    
    
    /**
     * Display the specified resource.
     */
    public function show(Client $woocommerce, string $id)
    {
        $product = $woocommerce->get("products/{$id}");
    
        // Define o nome do produto pai antes do loop
        $parent_name = $product->name;
        $parent_id = $product->id;
        if (!empty($product->variations)) {
            $variations = $woocommerce->get("products/{$id}/variations");
    
            foreach ($variations as $variation) {
                // Busca o relatório de criação baseado no ID da variação
                $repProd = ReportCreate::where('product_id', $variation->id)->first();
    
               
    
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
    
        return view("stock.stock-show")->with([
            'product' => $product,
            'parent_name' => $parent_name,
            'parent_id' => $parent_id
        ]);
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
    
     public function update(Request $request, Client $woocommerce, WpClient $wpService, )
     {
         $data = $request->all();
         if ($data['type'] === 'simple') {
             $this->updateSimple($request, $woocommerce, $wpService, );
             return redirect()->route('stock.show', ['id' => $data['id']]);
         } else {
           
             $this->updateVariations($data, $woocommerce,$wpService);
             return redirect()->route('stock.show', ['id' => $data['parent_id']]);
         }
     }
 
     private function updateVariations($data, Client $woocommerce, WpClient $wpService)
     {
      
         $variantData = [];
         $count = count($data['variant_price']);
         $imageIds = [];
     
         // Se o nome do produto pai foi alterado, faz um PUT para atualizar
         if ($data['old_parent_name'] != $data['parent_name']) {
             try {
                 $woocommerce->put("products/{$data['parent_id']}", [
                     'name' => $data['parent_name']
                 ]);
             } catch (\Exception $e) {
                 return back()->with('error', 'Erro ao atualizar o nome do produto pai.');
             }
         }
         
         // Loop para preparar os dados das variações
         for ($i = 0; $i < $count; $i++) {
             // Verificar se há imagens para a variação
             if (isset($data['images'][$i]) && $data['images'][$i] instanceof \Illuminate\Http\UploadedFile) {
                 // Enviar a imagem e obter o ID da imagem
                 $wordpressServiceProvider = new WordpressServiceProvider(app());
                 $imageIds[] = $wordpressServiceProvider->uploadWP($data['images'][$i], $data['id'][$i], $wpService); 
                 
             }
     
             // Preparar os dados da variação
             $variant = [
                'id' => $data['id'][$i],  // O id diretamente aqui
                
                'regular_price' => (string)str_replace(',', '.', $data['variant_price'][$i]),
                'stock_quantity' => $data['variant_stock_quantity'][$i],
            ];
    
            // Só adiciona o campo 'images' se houver imagens
            if (!empty($imageIds[$i])) {
                $variant['image'] = ['id' => $imageIds[$i]];
            }
    
            // Adiciona a variação aos dados
            $variantData[] = $variant;
        }
         // Estrutura da requisição para atualizar as variações
         $req = ['update' => $variantData];
  
         try {
             // Chamada à API WooCommerce para atualizar as variações
             $response = $woocommerce->post("products/{$data['parent_id']}/variations/batch", $req);
            
             if (isset($response->update)) {
                 foreach ($variantData as $index => $var) {
                     ReportCreate::where('product_id', $var['id'])->update([
                         'nome' => $data['parent_name'] ." ".$data['variant_name'][$index] ?? '',
                         'preco' => $var['regular_price'],
                         'estoque' => $data['variant_stock_quantity'][$index],
                         'estante' => $data['estante'][$index],
                         'prateleira' => $data['prateleira'][$index],
                     ]);
                 }
             }
     
             return back()->with('success', 'Produto e variações atualizados com sucesso!');
         } catch (\Exception $e) {
             return back()->with('error', 'Erro ao atualizar as variações do produto.');
         }
     }
     
 
     private function updateSimple(Request $request, Client $woocommerce, WpClient $wpService)
     {
         $data = $request->all();
         $image_id = null;
         if ($request->hasFile('image')) {
             $imageFile = $request->file('image');
             $wordpressServiceProvider = new WordpressServiceProvider(app());
             $image_id = $wordpressServiceProvider->uploadWP($imageFile,$data['name'], $wpService) ;
             
         }
         try {
             $id = $data['id'];
             $produto = [
                 'name' => $data['name'],
                 'regular_price' => (string)str_replace(',', '.', $data['price']),
                 'stock_quantity' => $data['quantity'],
                
             ];
             if($image_id){
                 $produto['images'] = [['id' => $image_id]];
             }
             
             $woocommerce->put("products/$id", $produto);
             ReportCreate::where('product_id', $id)->update([
                'nome' => $data['name'],
                'preco' => (string)str_replace(',', '.', $data['price']),
                'estoque' => $data['estoque'],
                'estante' => $data['estante'],
                'prateleira' => $data['prateleira']
            ]);
            
 
             return back()->with("warn", "Produto Editado com sucesso");
         } catch (\Exception $e) {
            
             return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsável');
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