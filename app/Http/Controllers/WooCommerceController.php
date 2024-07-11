<?php

namespace App\Http\Controllers;


use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{

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
    



    public function getProducts(Client $woocommerce)
    {
        try {
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function sellProducts(Client $woocommerce){
        


        return view("SellProduct");
    }

  /*  public function updateProducts(Request $request, Client $woocommerce){
        try {
            
                    $produtoFormat = '';

                    for ($i = 0; $i < strlen($request->product); $i++) {
                        $letra = $request->product[$i]; // Obtém o caractere atual
                        
                        if ($letra === '=') {
                            $letra .= ','; // Adiciona a vírgula após o '='
                        }
                        
                        $produtoFormat .= $letra; // Acumula os caracteres na nova string
                    }

            $produtoFormat = explode(",",$produtoFormat);
            array_pop($produtoFormat);
             $id_array = array();
            foreach($produtoFormat as $item){
               
                $id = $this->descriptografar($item, env("CRIPT"));
                array_push($id_array, $id);
            }
            
           
            
            $params = ['include' => $id_array];
            $products = $woocommerce->get('products', $params);
            dd($products);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } */
}
