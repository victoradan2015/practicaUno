<?php

namespace App\Http\Controllers\MobileApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\SecondAuthenticationCodes;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginCodeMail;
use App\Models\User;

class APILoginController extends Controller
{
    /* Login */
    public function generateToken(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //dd($credentials);

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('Token_de_prueba')->plainTextToken;
    
            return response()->json(['message' => 'Usuario Autenticado','token' => $token]);
        }
    
        return response()->json(['token' => ""], 401);
    }

    /* Api codigo */
    public function setCodeMobile(Request $request)
    {
        //$secondAuthenticationCode = new SecondAuthenticationCodes();
        $code = hash('sha256', $request->code);
        $tipo = 1; //1 es web

        $newCode = rand(10000,99999);

        $secondAuthenticationCode = (SecondAuthenticationCodes::where('code', $code)->first());

        //dd($secondAuthenticationCode);
        if ($secondAuthenticationCode != null) {
            
            $secondAuthenticationCode->update([
                'code' => hash('sha256', $newCode),
                'used' => false,
                'tipo' => $tipo,
            ]);
            
            $user = (User::where('id', $secondAuthenticationCode->id_usuario)->first());

            return response()->json(['message' => 'Codigo Actualizado','token' => $newCode]);
        }

        //dd($secondAuthenticationCode);

        return response()->json(['message' => 'Codigo Incorrecto','token' => ""]);
    }

    protected function sendLoginCodeEmail($user, $code)
    {
            $loginCodeMail = new LoginCodeMail($code);
            Mail::to($user->email)->send($loginCodeMail);
    }

}
