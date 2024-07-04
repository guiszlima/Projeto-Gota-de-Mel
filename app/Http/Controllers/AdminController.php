<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
class AdminController extends Controller
{
    public function index(){
        $usuarios_pendentes = User::where('is_pending', true)->get();
        $roles = Role::all();
        return view('admin.users-admin')
        ->with('usuarios_pendentes', $usuarios_pendentes)
        ->with('roles', $roles);
    }
    public function acceptUsers(Request $requests){
        $user = User::findOrFail($requests->user_id);
        
        $user->is_pending = false;
        $user->role_id = $requests->role;
        $user->save();
        return redirect()->route('admin');
    }
}
