<?php

namespace App\Http\Controllers;

use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Client as WpClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use phpDocumentor\Reflection\PseudoTypes\True_;

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
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255',
        'price' => 'required|string',
        'stock_quantity' => 'required|integer',
        'description' => 'nullable|string',
        'height' => 'nullable|numeric',
        'width' => 'nullable|numeric',
        'depth' => 'nullable|numeric',
        'weight' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp',
        'category'=> 'required|numeric',
    ]);

    // Processar o upload da imagem
    if ($request->hasFile('image')) {
        // Obtém o caminho do arquivo da imagem
        $requestImage = $request->image;
        $extension = $requestImage->extension();
        $imageName = $validatedData['name'] . strtotime('now') . '.' . $extension;
        $imgPath = public_path('img/temp_imgs/' . $imageName);
        $requestImage->move(public_path('img/temp_imgs'), $imageName);
       
        // Caminho da imagem no seu projeto Laravel


        // Caminho da imagem no seu projeto Laravel
       
        $e_commerce=env('WOOCOMMERCE_STORE_URL');
        $request = '/wp-json/wp/v2/media';
        $request_url = $e_commerce.$request;
        // Fazer a requisição de upload da imagem
        
        $response = $wpService->request('POST', $request_url, [
            'headers' => [
                'Content-Disposition' => 'attachment; filename="' . basename($imgPath) . '"',
                'Content-Type' => "image/$extension",
            ],
            'body' => fopen($imgPath, 'r'), // Enviar o conteúdo do arquivo
            'auth' => [env('ADMIN_NAME'), env('ADMIN_PASSWORD')], // Autenticação básica com usuário e senha
            
        ]);
        
        $image_data = json_decode($response->getBody(), true);
        $image_id = $image_data['id']; // Obter o ID da imagem
        

        unlink($imgPath);
        
        $productData = [
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'manage_stock'=>true,
            'regular_price' => $validatedData['price'],
            'stock_quantity' => $validatedData['stock_quantity'],
            'description' => $validatedData['description'],
            'dimensions' => [
                'height' => $validatedData['height'] ?? '',
                'width' => $validatedData['width'] ?? '',
                'length' => $validatedData['depth'] ?? '',
            ],
            'weight' => $validatedData['weight'] ?? '',
            'categories' => [
                [
                    'id' => $validatedData['category']
                ],
            ],
            'images' => [
                [
                    'id' => $image_id
                ],
                ]
            ];
    
    }

    else{
        $productData = [
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'regular_price' => $validatedData['price'],
            'manage_stock'=>true,
            'stock_quantity' => $validatedData['stock_quantity'],
            'description' => $validatedData['description'],
            'dimensions' => [
                'height' => $validatedData['height'] ?? '',
                'width' => $validatedData['width'] ?? '',
                'length' => $validatedData['depth'] ?? '',
            ],
            'weight' => $validatedData['weight'] ?? '',
            'categories' => [
                [
                    'id' => $validatedData['category']
                ],
            ],
           
            ]; 
    }
        
    try {
        // Enviar a requisição para criar o produto na API do WooCommerce
        $response = $woocommerce->post('products', $productData);

        // Opcional: Verifique a resposta e execute ações conforme necessário
        dd($response); // Apenas para fins de depuração
    } catch (\Exception $e) {
        // Lide com exceções, como erros de rede ou falhas na API
        dd($e->getMessage());
        return back()->withErrors(['error' => 'Erro ao criar o produto: ' . $e->getMessage()]);
    }
}
public function createVariableProduct(Client $woocommerce) {
    // Obter os atributos de produtos
    $currentRoute = Route::currentRouteName();
   

    
    // Passar os atributos organizados para a view
    return view('stock.create-variable-product')->with('currentRoute',$currentRoute); 
}

public function storeVariableproduct(Client $woocommerce, Request $request,WpClient $wpService){
    
    $data = $request->all();
  
    // Processar o upload da imagem
    if ($request->hasFile('image')) {
        // Obtém o caminho do arquivo da imagem
        $requestImage = $request->image;
        $extension = $requestImage->extension();
        $imageName = $request['productName'] . strtotime('now') . '.' . $extension;
        $imgPath = public_path('img/temp_imgs/' . $imageName);
        $requestImage->move(public_path('img/temp_imgs'), $imageName);
       
        // Caminho da imagem no seu projeto Laravel


        // Caminho da imagem no seu projeto Laravel
       
        $e_commerce=env('WOOCOMMERCE_STORE_URL');
        $request = '/wp-json/wp/v2/media';
        $request_url = $e_commerce.$request;
        // Fazer a requisição de upload da imagem
        
        $response = $wpService->request('POST', $request_url, [
            'headers' => [
                'Content-Disposition' => 'attachment; filename="' . basename($imgPath) . '"',
                'Content-Type' => "image/$extension",
            ],
            'body' => fopen($imgPath, 'r'), // Enviar o conteúdo do arquivo
            'auth' => [env('ADMIN_NAME'), env('ADMIN_PASSWORD')], // Autenticação básica com usuário e senha
            
        ]);
        
        $image_data = json_decode($response->getBody(), true);
        $image_id = $image_data['id']; // Obter o ID da imagem
        

        unlink($imgPath);
        
        $createProd = [
            'name' => $request['productName'],
            'type' => 'variable',
            'description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.',
            'short_description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
            'categories' => [
                [
                    'id' => 9
                ],
                [
                    'id' => 14
                ]
            ],
            'images' => [
                [
                    'id'=> 42
                ],
                [
                    'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg'
                ]
            ]
        ];
        
       
    
   
            // Data foi inicializado no começo da função
            $variantData = [];
            $count = count($data['id_term']);
        
            for ($i = 0; $i < $count; $i++) {
                $variantData['create'][] = [
                    'regular_price' => $data['preco'][$i],
                    'sku' => $data['sku'][$i],
                    'stock_quantity' => $data['quantity'][$i],
                    'attributes' => [
                        [
                            'id' => (int)$data['attribute_dad'][0], // Ajuste este ID conforme necessário
                            'option' =>  $data['nome'][$i], // Ajuste este valor conforme necessário
                        ],
                    ],
                    'images' => [
                        [
                            'id' => $image_id,
                        ],
                    ]
                ];
                    }
                    $wooresponse =  $woocommerce->post("products/{$data['attribute_dad'][0]}/variations/batch", $variantData);
                    dd($wooresponse);
    } 
    else{
        $variantData = [];
            $count = count($data['id_term']);
        
            for ($i = 0; $i < $count; $i++) {
                $variantData['create'][] = [
                    'regular_price' => $data['preco'][$i],
                    'sku' => $data['sku'][$i],
                    'stock_quantity' => $data['quantity'][$i],
                    'attributes' => [
                        [
                            'id' => (int)$data['attribute_dad'][0], // Ajuste este ID conforme necessário
                            'option' =>  $data['nome'][$i], // Ajuste este valor conforme necessário
                        ],
                    ],
                   
                ];
                    }
    
                   $wooresponse = $woocommerce->post("products/{$data['attribute_dad'][0]}/variations/batch", $variantData);
                   dd($wooresponse);
                }         
    }
      


// Processa os arquivos únicos







    
    


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