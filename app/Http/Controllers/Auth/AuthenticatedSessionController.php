<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use App\Http\Controllers\SecondAuth\SecondAuthenticationController;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Support\Facades\Mail;
use App\Mail\LoginCodeMail;
use App\Http\Requests\Auth\Log;
use App\Models\User;
use App\Http\Controllers\SecondAuth\SecondAuthenticationController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try{
            $secondAuth = new SecondAuthenticationController();
            
            $email = $request->input('email');
            $password = $request->input('password');

            $credentials = compact('email', 'password');

            if (Auth::validate($credentials)) {
                $rol = $this->getRolUsuario($request->email);
                session(['credentials' => $credentials]);
                if($rol == 1) // En caso de ser rol 2 (Admin) Implementa proceso para segunda auth, la que se encargar de loggearlo o no
                {
                    app()->call([$secondAuth,'generateCodeSecondAuthenticationCode'],['request' => $request]);
                    return redirect()->route('second_auth');
                }
                if($rol == 2) // En caso de ser rol 2 (normal) directamente iniciara la session
                {
                    $request->authenticate();
                    $request->session()->regenerate();
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
                else 
                {
                    $request->authenticate();
                    $request->session()->regenerate();
                    //return redirect()->intended(RouteServiceProvider::HOME)->with('rol', 'admin');
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        catch (ModelNotFoundException $e)
        {
            return redirect()->route('login')->with('error', 'Ocurrió un error al guardar los datos. Por favor, inténtalo de nuevo.');
        }
        catch (ValidationException $e) {
            return redirect()->route('login')->withErrors($e->errors())->withInput($request->except('password'));
        }
        catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Ocurrió un error al guardar los datos. Por favor, inténtalo de nuevo.');
        }
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function getRolUsuario($correo)
    {
        $rol = (User::where('email', $correo)->first())->rol;
        return $rol;
    }

    

}
