<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use App\Models\Produtos;

class BarCodeMakerController extends Controller
{
   public function index(Request $request, Client $woocommerce)
   {
      // Recuperando o número da página atual da requisição
      $busca = $request->input('busca') ?? null;
        $searchType = $request->input('searchType') ?? null;// nome ou sku
        $nmbrPerPage = 10;
    
        // Se não tiver busca, deixa os parâmetros nulos para retornar tudo
        $parametroNome = $busca ? $searchType : null;
        $parametroValor = $busca;
        

        // Busca produtos com paginação
        $products = Produtos::listProducts($parametroNome, $parametroValor, $nmbrPerPage, true);
    
        return view('barcode.barcode-index', compact('products', 'busca', 'searchType'));
      
   }

   public function generate(Request $request , Client $woocommerce){
      
      $nome_sem_espacos = str_replace(' ', '_', trim($request->name));
      if ($request->variations) {
         
         
         
         
             $variations = Produtos::listVariationsById($request->id);
         
         
         
         
         
         
         
         return view('barcode.generate-var')->with('variations', $variations)->with('parent_name',$request->name);

      }else{
      $product = [
         'sku'=>$request->sku,
         'price'=> $request->price,
         'name' => $nome_sem_espacos,
         'real_name' => $request->name,
      ];
      return view('barcode.generate')->with('product', $product);
   }
   }
}