<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingUser;
class AdminController extends Controller
{
    public function index(){
        $pendingUsers = PendingUser::all();
        return view('admin.admin')->with('usuarios_pendentes', $pendingUsers);
    }
    public function registerUsers(){
        
    }
}
