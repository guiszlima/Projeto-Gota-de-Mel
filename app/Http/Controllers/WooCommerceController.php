<?php

namespace App\Http\Controllers;


use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{

   
    



    public function getProducts(Client $woocommerce)
    {
        try {
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function sellProducts(){
        


        return view("sell.sell-product");
    }

    public function payment(Request $request){
        dd($request->all());
    }
}