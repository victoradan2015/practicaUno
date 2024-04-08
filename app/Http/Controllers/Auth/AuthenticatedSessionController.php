<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            
            /*
            $accesoCorrecto = $this->verificaAccesoRol($request);
            //if ( $accesoCorrecto == false ) {
            if ( $accesoCorrecto == true ) {
                return redirect()->route('login')->with('error', 'Acceso desde lugar incorrecto para tu Rol.');
            }
            */

            $email = $request->input('email');
            $password = $request->input('password');

            $credentials = compact('email', 'password');

            if (Auth::validate($credentials)) {
                $rol = $this->getRolUsuario($request->email);
                session(['credentials' => $credentials]);
                dd("Valida");
                if($rol == "administrador") // Implementa proceso para segunda auth, la que se encargar de loggearlo uno vez ponga el codigo
                {
                    $tipo = 2;
                    app()->call([$secondAuth,'generateCodeSecondAuthenticationCode'],['request' => $request, 'tipo' => $tipo]);
                    return redirect()->route('second_auth');
                } else
                if($rol == "coordinador") // Implementa proceso para segunda auth, la que se encargar de loggearlo uno vez ponga el codigo
                {
                    $tipo = 1;
                    app()->call([$secondAuth,'generateCodeSecondAuthenticationCode'],['request' => $request, 'tipo' => $tipo]);
                    return redirect()->route('second_auth');
                } else
                if($rol == "invitado") // En caso de ser rol invitado directamente iniciara la session
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
            abort(400,"error uno");
            //return redirect()->route('login')->with('error', 'Ocurrió un error al guardar los datos. Por favor, inténtalo de nuevo.');
        }
        catch (ValidationException $e) 
        {
            abort(400,"error dos");
            //return redirect()->route('login')->withErrors($e->errors())->withInput($request->except('password'));
        }
        catch (\Exception $e) 
        {
            abort(400,"error tres");
            //return redirect()->route('login')->with('error', 'Ocurrió un error al guardar los datos. Por favor, inténtalo de nuevo.');
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
        $user = (User::where('email', $correo)->first());
        $rol = $user->getRoleNames()->first(); //En este caso solo traera el primero, el sistema no esta diseniado para varios roles
        return($rol);
    }

    /* Consulta el rol del usuario y la ip a la que accede, si es correcta su ip a la que accedio de acuerdo a su rol, retorna true, si no, false */
    public function verificaAccesoRol(Request $request){
        try {
            $rol = $this->getRolUsuario($request->email);
            $ip_dominio = "trabajadores.store";
            $ip_vpn_proxi = ""; // vpn del droplet del proxy
            $ip_acceso = $request->server('HTTP_HOST');
            
            if ( $rol == "administrador" and $ip_acceso == $ip_vpn_proxi or                                     //administrador entra por vpn
                ($rol == "coordinador" and $ip_acceso == $ip_dominio or $ip_acceso == $ip_vpn_proxi ) or        //coordinador entra por vpn y por dominio
                $rol == "invitado" and $ip_acceso == $ip_vpn_proxi )                                            //invitado entra por dominio
            {
                return true;
            } else
            {
                return false;
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Ocurrio un error verificando ip de acceso.');
        }

    }

}
