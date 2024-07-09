<?php

namespace App\Http\Controllers;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
class BarCodeMakerController extends Controller
{
    function encryptStringWithShortKey($textoOriginal, $chave) {
        // Chave deve ter exatamente 8 bytes
        $chave = substr($chave, 0, 8);
    
        // Gerar um IV (Initialization Vector) aleatório
        $iv = random_bytes(8); // 8 bytes = 64 bits (tamanho mínimo para DES)
    
        // Criptografar usando DES com a chave de 8 bytes
        $textoCriptografado = openssl_encrypt($textoOriginal, 'des-ecb', $chave, OPENSSL_RAW_DATA, $iv);
    
        // Retornar o IV e o texto criptografado em um array para possível descriptografia posterior
        return [
            'iv' => base64_encode($iv),
            'textoCriptografado' => base64_encode($textoCriptografado),
        ];
    }
    public function testing(Client $woocommerce){

        $products = $woocommerce->get('products/72');
        $barCode = [
            "id" => $products->id,
            "price" => $products->price,

        ];
        $chave = '123456';

       $barCode = json_encode($barCode);
       
       $encrypted = $this->encryptStringWithShortKey($barCode,$chave );
        dd($encrypted);

    }
}
