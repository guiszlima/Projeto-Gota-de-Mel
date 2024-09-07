<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportCreate;
class ReportController extends Controller
{
    public function get(){
      


        return view('report/report');
        
    }
}