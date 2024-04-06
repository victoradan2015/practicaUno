<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Http\Controllers\SecondAuth\SecondAuthenticationController;

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
        $secondAuth = new SecondAuthenticationController();
        $cantidadUsuarios = User::count();

        /*  Si la cantidad de usuarios es 0, se asigna rol administrador, 
            si hay 1 (admin) el segundo entra como coordinador,
            todos los demas usuarios por defecto se crea con rol de invitado
        */
        //dd($cantidadUsuarios);
        if ($cantidadUsuarios == 0) {
            $rol_nombre = 'administrador';
            //app()->call([$secondAuth,'generateCodeSecondAuthenticationCode'],['request' => $request]);
        }
        else if ($cantidadUsuarios == 1) {
            $rol_nombre = 'coordinador';
            //app()->call([$secondAuth,'generateCodeSecondAuthenticationCode'],['request' => $request]);
        }
        else {
            $rol_nombre = 'invitado';
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => 'required|captcha',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 0,
            ]);
            // Despues de crear al usuario le asigno un rol
            $user->assignRole($rol_nombre);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario.');
        }

        

        event(new Registered($user));

        //Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
