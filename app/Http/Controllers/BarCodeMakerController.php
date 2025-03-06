<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;

class BarCodeMakerController extends Controller
{
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
      try {
         $totalProducts = $responseHeaders['X-WP-Total'];
      } catch (\Throwable $th) {
         $totalProducts = $responseHeaders['x-wp-total'];
      }
     
      // Calculando o número total de páginas
      $totalPages = ceil($totalProducts / $nmbrPerPage);
      // Retornando a view com os produtos e informações de paginação
      return view("barcode.barcode-gen", [
         'products' => $products,
         'currentPage' => $page,
         'totalPages' => $totalPages
      ]);
   }

   public function generate(Request $request , Client $woocommerce){
      
      $nome_sem_espacos = str_replace(' ', '_', trim($request->name));
      if ($request->type == 'variable') {
         $all_variations = [];
         $page = 1;
         
         do {
             $variations = $woocommerce->get("products/{$request->id}/variations", [
                 'per_page' => 100,
                 'page' => $page
             ]);
         
             $all_variations = array_merge($all_variations, $variations);
             $page++;
         } while (count($variations) === 100); // Continua até que menos de 100 resultados sejam retornados.
         
         
         
         return view('barcode.generate-var')->with('variations', $variations)->with('parent_name',$request->name);

      }else{
      $product = [
         'sku'=>$request->sku,
         'price'=> $request->price,
         'name' => $nome_sem_espacos
      ];
      return view('barcode.generate')->with('product', $product);
   }
   }
}