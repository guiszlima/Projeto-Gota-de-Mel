<?php

namespace App\Http\Controllers;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
class BarCodeMakerController extends Controller
{


   protected function criptografar($textoOriginal, $chave) {
        // Chave deve ser exatamente 16 bytes (128 bits) para AES-128
        $chave = substr(hash('sha256', $chave, true), 0, 16);
    
        // Gerar um IV (Initialization Vector) de 16 bytes
        $iv = openssl_random_pseudo_bytes(16);
    
        // Criptografar o texto original usando AES-128-CBC
        $ciphertext = openssl_encrypt($textoOriginal, 'aes-128-cbc', $chave, OPENSSL_RAW_DATA, $iv);
    
        // Retornar o IV e o texto criptografado, ambos codificados em Base64
        return base64_encode($iv . $ciphertext);
    }
    protected function descriptografar($textoCriptografado, $chave) {
        // Chave deve ser exatamente 16 bytes (128 bits) para AES-128
        $chave = substr(hash('sha256', $chave, true), 0, 16);
    
        // Decodificar o texto criptografado de Base64
        $dados = base64_decode($textoCriptografado);
    
        // Extrair o IV e o texto criptografado
        $iv = substr($dados, 0, 16);
        $ciphertext = substr($dados, 16);
    
        // Descriptografar o texto criptografado usando AES-128-CBC
        return openssl_decrypt($ciphertext, 'aes-128-cbc', $chave, OPENSSL_RAW_DATA, $iv);
    }
    public function testing(Client $woocommerce){

        $products = $woocommerce->get('products/72');
        $barCode = $products->id;
        

       
       
       $encrypted = $this->criptografar($barCode,'123');
       $descripted = $this->descriptografar($encrypted,'123');
        dd("texto criptografado: ",$encrypted,"\nTexto Descriptografado: ", $descripted);

    }
}
