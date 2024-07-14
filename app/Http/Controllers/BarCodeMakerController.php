<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarCodeMakerController extends Controller
{
   public function index(){
     return view("barcode-gen");
   }
}
