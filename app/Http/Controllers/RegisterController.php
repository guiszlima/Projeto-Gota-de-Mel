<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingUser;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    # Show Register form
    public function index(){
        return view("auth.register");
    }
    public function registerUser(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Este endereço de e-mail já está em uso.',
            // Outras mensagens de erro personalizadas conforme necessário
        ]);
      
        $message = "Suas informações foram enviadas para o administrador responsável pela Autorização, Favor Aguardar";
        
        $password = Hash::make($validatedData['password']);
        $pUser = PendingUser::Create([
            'name'=> $validatedData['name'],
            'email'=> $validatedData['email'],
            'password'=> $password
        ]);
        return redirect()->route("login")->with('message',$message);
    }
   
}
