<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email', 'unique:' . PendingUser::class . ',email'],
            'CPF' => ['required','cpf', 'string', 'unique:' . User::class . ',CPF', 'unique:' . PendingUser::class . ',CPF'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $pendingUser = PendingUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $message = "Suas informações foram enviadas para o administrador responsável pela Autorização, Favor Aguardar";

        return redirect()->route("login")->with('wait',$message);
    
       /**  event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
        **/
    }
}
