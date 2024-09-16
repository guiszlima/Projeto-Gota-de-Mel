<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
class AdminController extends Controller
{
public function index(){
    $users = $usuarios_pendentes = User::where('is_pending', false)->where('id','!=',1)->get();
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


    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,id', // Validação para role
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $request->input('role');
        $user->save();

        return redirect()->route('admin.index')->with('success', 'Cargo atualizado com sucesso');
    }
    public function acceptUsers(Request $request){
     
        $user = User::findOrFail($request->user_id);
        
        $user->is_pending = false;
        $user->role_id = $request->role;
        $user->save();
        return redirect()->back();
    }
    public function deleteUsers($id){

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back();
    }
}