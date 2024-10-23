<?php

namespace App\Http\Controllers;

use App\Models\ReportSell;
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
      
       $sell = $request->all();
     
       $cartItems = json_decode($request->input('cart'), true);
       
    
      
             return view('payment')->with('sell',$sell);
    
}
}