<?php

namespace App\Http\Controllers;

use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    protected $woocommerce;

    public function __construct()
    {
        $consumer_key = "ck_0ec7f6089720f4710bd073d5e44ac6e1ee3c5133";
        $consumer_secret = "cs_fe3a4dcbc9b14eebf6f43274c794b9ae959b80f5";
        $store_url = "http://localhost:10013";

        $this->woocommerce = new Client(
            $store_url,
            $consumer_key,
            $consumer_secret,
            [
                'version' => 'wc/v3',
            ]
        );
    }

    public function getProducts()
    {
        try {
            $products = $this->woocommerce->get('products');
            
            dd($products[0]->stock_quantity); // Retorna os produtos como JSON
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
