<?php
namespace App;
use GuzzleHttp\Client as WpClient;
use Automattic\WooCommerce\Client;

class WordpressClass
{
    public $request;
    public $woocommerce;
    public $wpService;
    public function EditarImagem()
    {
         // Buscar o produto pelo parent_id
        $parentId = $this->request['parent_id']?? $this->request['id'];
        $product = $this->woocommerce->get("products/$parentId");
        $e_commerce = env('WOOCOMMERCE_STORE_URL');
        $request = '/wp-json/wp/v2/media';
        $request_url = $e_commerce . $request;
        
        // Verificar se o produto já tem uma imagem
        if (!empty($product->images)) {
            $currentImageId = $product->images[0]->id; // Pegando o ID da primeira imagem
            $delete_url = $request_url.'/'.$currentImageId. '?force=true';
   
            // Deletar a imagem atual
            $response = $this->wpService->request('DELETE', $delete_url, [
                'auth' => [env('ADMIN_NAME'), env('ADMIN_APP_PASSWORD')], // Autenticação básica com usuário e senha
            ]);
        }

        // Preparar a nova imagem
        $requestImage = $this->request['image'];
        $extension = $requestImage->extension();
        $imageName = $product->name . strtotime('now') . '.' . $extension;
        $imgPath = public_path('img/temp_imgs/' . $imageName);
        $requestImage->move(public_path('img/temp_imgs'), $imageName);

        // Caminho da imagem no seu projeto Laravel
      

        // Fazer a requisição de upload da imagem para o WordPress
        $response = $this->wpService->request('POST', $request_url, [
            'headers' => [
                'Content-Disposition' => 'attachment; filename="' . basename($imgPath) . '"',
                'Content-Type' => "image/$extension",
            ],
            'body' => fopen($imgPath, 'r'),
            'auth' => [env('ADMIN_NAME'), env('ADMIN_APP_PASSWORD')], // Certifique-se de usar a senha de aplicação
        ]);
        

        $imageData = json_decode($response->getBody(), true);
        $imageId = $imageData['id']; // Obter o ID da imagem

        // Associar a nova imagem ao produto no WooCommerce
        $this->woocommerce->put("products/$parentId", [
            'images' => [
                [
                    'id' => $imageId
                ]
            ]
        ]);

        // Remover a imagem temporária do servidor
        unlink($imgPath);
    }

    public function __construct($request,$woocommerce,$wpService)
    {
         $this->request = $request;
         $this->woocommerce = $woocommerce;
        $this->wpService = $wpService;
       
    }
}