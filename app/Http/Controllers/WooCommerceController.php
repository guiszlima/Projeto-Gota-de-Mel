<?php

namespace App\Http\Controllers;


use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    protected $woocommerce;



    public function getProducts(Client $woocommerce)
    {
        try {
            $products = $woocommerce->get('products');
            
            dd($products[0]); // Retorna os produtos como JSON
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function sellProducts($id){
        $product = $this->woocommerce->get("products/{$id}");


        return view("edit_product")
        ->with("product",$product);
    }

    public function updateProducts(Request $request){
        try {
            $id = $request->id_from_request;
            $products = $this->woocommerce->get("products/{$id}");
            $before_sell =   $products->stock_quantity;
            $after_sell = $before_sell-1;
            $data = [
                'stock_quantity' => $after_sell
            ];
            
            $update = $this->woocommerce->put("products/{$id}", $data);
        echo 'Feito Corretamente' ;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
