<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as WpClient;
class WordpressServiceProvider extends ServiceProvider
{
    /**
     * Faz Upload da imagem e cria no woocommerce 
     */

    public function uploadWP($imagem,$nomeProduto,WpClient $wpService)
    {
        $requestImage = $imagem;
        $extension = $requestImage->extension();
        $imageName = $nomeProduto . strtotime('now') . '.' . $extension;
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
        $image_id = $image_data['id'];
        unlink($imgPath);
        return $image_id;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}