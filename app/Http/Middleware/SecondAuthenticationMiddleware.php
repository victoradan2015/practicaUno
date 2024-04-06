<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;
use App\Models\SecondAuthenticationCodes;

class SecondAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd('LLega hasta aqui, este es el usuario',$request->user()->email);
        $id_usuario = $this->getIdUsuario($request->user()->email);
        $statusCode = $this->getStatusSecondAuthentication($id_usuario);
        $rol_usuario = $this->getRolUsuario($id_usuario);

        //dd('status del usuario',$idRol);
        
        if(($rol_usuario == "administrador" || $rol_usuario == "coordinador") and $statusCode == 0) {
            return redirect()->route('second_auth');
            //return $next($request);
        }else{
            return $next($request);
        }
        
    }

    protected function getIdUsuario($correo)
    {
        $id = (User::where('email', $correo)->first())->id;
        return $id;
    }

    protected function getRolUsuario($id_usuario)
    {
        $user = (User::where('id', $id_usuario)->first());
        $rol = $user->getRoleNames()->first(); //En este caso solo traera el primero, el sistema no esta diseniado para varios roles
        return($rol);
    }

    protected function getStatusSecondAuthentication($id_usuario)
    {
        $userSecondAuth = (SecondAuthenticationCodes::where('id_usuario', $id_usuario)->first());

        if ($userSecondAuth !==  null) {
            $status = $userSecondAuth->used;
        }else{
            $status = 1;
        }
        
        return $status;
    }

    

}
