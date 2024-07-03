<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
class AdminController extends Controller
{
    public function index(){
        $usuarios_pendentes = User::where('is_pending', false)->get();
        $roles = Role::all();
        return view('admin.users-admin')
        ->with('usuarios_pendentes', $usuarios_pendentes)
        ->with('roles', $roles);
    }
    public function acceptUsers(Request $requests){
        dd($requests->all());
        
    }
}
