<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
class AdminController extends Controller
{
public function index(){
    $users = User::all();
    $currentRoute = Route::currentRouteName();
    $roles = Role::all();
    return view("admin.admin-index")
    ->with("users", $users)
    ->with('roles', $roles)
    ->with("currentRoute", $currentRoute);
}


    # Accept or Deny Users
    public function accept_index(){
        $usuarios_pendentes = User::where('is_pending', true)->get();
        $roles = Role::all();
        
        $currentRoute = Route::currentRouteName();
        return view('admin.users-admin')
        ->with('usuarios_pendentes', $usuarios_pendentes)
        ->with('roles', $roles)
        ->with("currentRoute", $currentRoute);

    }
    public function acceptUsers(Request $requests){
        $user = User::findOrFail($requests->user_id);
        
        $user->is_pending = false;
        $user->role_id = $requests->role;
        $user->save();
        return redirect()->route('admin.index.accept');
    }
    public function deleteUsers($id){

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.index.accept');
    }
}
