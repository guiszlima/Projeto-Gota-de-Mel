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
        $products = $woocommerce->get('products', $params);
        
        
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
        ReportCreate::create([
            'product_id' => $response->id,
            'nome' => $response->name,
            'estoque'=> $request->estoque,
            'estante' => $request->estante,
            'prateleira'=>$request->prateleira,
            'preco' => $response->price,
            'type'=> 'simples'
        ]);
        // Opcional: Verifique a resposta e execute ações conforme necessário
        return back()->with("warn", "Produto Registrado com sucesso");
    } catch (\Exception $e) {
        // Lide com exceções, como erros de rede ou falhas na API
       
        return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsavel' );
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

   $attribute_name = $data['nameAttribute'];
    $attribute_id =(int)$data['attribute_dad'][0];

    // Processar o upload da imagem
    if ($request->hasFile('imagem')) {
        // Obtém o caminho do arquivo da imagem
        $requestImage = $request->imagem;
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
            'name' => $data['nomeProduto'],
            'type' => 'variable',
            'description' =>$data['description']??'',
            'categories' => [
                [
                    'id' => $data['category']
                ]
              
            ],
            'images' => [
                [
                    'id'=> $image_id
                ],
                
            ],
            'attributes' => [
                [
                    'id' => $attribute_id, // ID do atributo
                    'name' => $attribute_name, // Nome do atributo, se aplicável
                    'options' => $data['nome'], // Opções do atributo
                    'position' => 0, // Posição do atributo
                    'visible' => true, // Se o atributo é visível no frontend
                    'variation' => true // Se o atributo é usado para variações
                ],
            ],
    
        ];
        
        $prodResponse = $woocommerce->post('products', $createProd);
    
   
            // Data foi inicializado no começo da função
            $variantData = [];
            $count = count($data['id_term']);
        
            for ($i = 0; $i < $count; $i++) {
                $variantData['create'][] = [
                    'regular_price' => $data['preco'][$i],
                    'sku' => $data['sku'][$i],
                    'stock_quantity' => $data['quantity'][$i],
                    'manage_stock' =>true,
                    'attributes' => [
                        [
                            'id' =>  $attribute_id,// Ajuste este ID conforme necessário
                            'option' =>  $data['nome'][$i], // Ajuste este valor conforme necessário
                        ],
                    ],
                    'images' => [
                        [
                            'id' => $image_id,
                        ],
                    ],
                    "dimensions"=> [
                        "weight"=> $data['weight']?? '',
                        "width"=> $data['width']?? '',
                        "height"=> $data['height']?? ''
                    ],
                ];
                    } 
                     $wooresponse = $woocommerce->post("products/{$prodResponse->id}/variations/batch", $variantData);
                     try {
                        if (isset($wooresponse->create)) {
                            // Inicialize o índice
                            $index = 0;
                    
                            foreach ($wooresponse->create as $index => $variation) {
                          
                                $estoque = $data['estoque'][$index];
                                $estante = $data['estante'][$index];
                                $prateleira = $data['prateleira'][$index];
                    
                                ReportCreate::create([
                                    'product_id' =>  $variation->id, 
                                    'estoque' => $estoque,
                                    'estante' => $estante,
                                    'prateleira' => $prateleira,
                                    'nome' => $data['nomeProduto'] ." ". $variation->name, 
                                    'preco' => $variation->price, 
                                    'type' => 'variante' 
                                ]);
                    
                                
                            
                            }
                        }
                        return back()->with("warn", "Produto Registrado com sucesso");
                    }catch (\Exception $e) {
                       
                        return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsavel' );
                       
                    }
                
                   
    } 
    else{
// Não tem imagem

        $createProd = [
            'name' => $data['nomeProduto'],
            'type' => 'variable',
            
            'description' =>$data['description']??'',
            'categories' => [
                [
                    'id' => $data['category']
                ]
              
            ],
            'attributes' => [
                [
                    'id' => $attribute_id, // ID do atributo
                    'name' => $attribute_name, // Nome do atributo, se aplicável
                    'options' => $data['nome'], // Opções do atributo
                    'position' => 0, // Posição do atributo
                    'visible' => true, // Se o atributo é visível no frontend
                    'variation' => true // Se o atributo é usado para variações
                ],
            ],
            
        ];
        
        $prodResponse = $woocommerce->post('products', $createProd);
        


        $variantData = [];
            $count = count($data['id_term']);
        
            for ($i = 0; $i < $count; $i++) {
                $variantData['create'][] = [
                    'name' =>  $data['nome'][$i],
                    'regular_price' => $data['preco'][$i],
                    'sku' => $data['sku'][$i],
                    'manage_stock' =>true,
                    'stock_quantity' => $data['quantity'][$i],
                    'attributes' => [
                        [
                            'id' =>  $attribute_id, // Ajuste este ID conforme necessário
                            
                            'option' =>  $data['nome'][$i], // Ajuste este valor conforme necessário
                        ],
                    ],
                    "dimensions"=> [
                        "weight"=> $data['weight'],
                        "width"=> $data['width'],
                        "height"=> $data['height']
                    ],
                   
                ];
                    }
                    $wooresponse = $woocommerce->post("products/{$prodResponse->id}/variations/batch", $variantData);
                  
                    try {
                       
                        if (isset($wooresponse->create)) {
                            // Inicialize o índice
                            $index = 0;
                    
                            foreach ($wooresponse->create as $index => $variation) {
                                // Verifique se os campos de dados não são arrays
                                $estoque = $data['estoque'][$index];
                                $estante = $data['estante'][$index];
                                $prateleira = $data['prateleira'][$index];
                    
                                ReportCreate::create([
                                    'product_id' =>  $variation->id, // ID do produto pai
                                    'estoque' => $estoque,
                                    'estante' => $estante,
                                    'prateleira' => $prateleira,
                                    'nome' => $data['nomeProduto'] ." ". $variation->name, // Nome da variação
                                    'preco' => $variation->price, // Preço da variação
                                    'type' => 'variante' // Tipo, como 'variante'
                                ]);
                    
                                // Incrementa o índice
                            
                            }
                        }
                       
                        return back()->with("warn", "Produto Registrado com sucesso");
                    }catch (\Exception $e) {
                       
                        return back()->with('warn', 'Erro ao criar o produto favor contatar o desenvolvedor responsavel' );
                       
                    }
                
                }         
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