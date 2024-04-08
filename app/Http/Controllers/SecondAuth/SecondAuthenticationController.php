<?php

namespace App\Http\Controllers\SecondAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SecondAuthenticationCodes;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginCodeMail;
use Illuminate\Support\Facades\Auth;

class SecondAuthenticationController extends Controller
{
    public function generateCodeSecondAuthenticationCode(Request $request, int $tipo){
        
        $secondAuthenticationCode = new SecondAuthenticationCodes();
        dd("entra modelo secondauth");
        try{
            $id_usuario = $this->getIdUsuario($request->email);
            
            $code = rand(10000,99999);
            $tipo = $tipo; // Tipo de plataforma: 1 = web, 2 = mobil
            //dd($tipo);
            $secondAuthenticationCode::updateOrInsert(
                ['id_usuario' => $id_usuario],
                [
                    'code' => hash('sha256', $code),
                    'id_usuario' => $id_usuario,
                    'used' => false,
                    'tipo' => $tipo,
                ]
            );

            $this->sendLoginCodeEmail($request, $code);

        } 
        catch(\Exception $e){
            //return redirect()->route('dashboard')->with('error', 'Error al registrar al nuevo usuario, vuelva a intentarlo mas tarde' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al iniciar session, vuelva a intentarlo mas tarde');
        }        
    }

    public function showViewSecondAuthCode(){
        return view('secondAuth.code-second-auth');
    }

    public function setSecondAuthCode(Request $request){
        try{/*
            $request->validate([
                'campo' => 'required|string|max:5|regex:{5}',
            ]);*/
            
            $id_usuario = $this->getIdUsuario(session('credentials.email'));
            
            $secondAuthenticationCode = SecondAuthenticationCodes::where('id_usuario', $id_usuario)->first();
            $codeInput = hash('sha256', $request->campo);
            $codeDB = $secondAuthenticationCode->code;
            $codeTipoDB = $secondAuthenticationCode->tipo; // tipo de plataforma: 1 = web, 2 = mobil
            //$user = User::where('email', session('credentials.email'))->first();
            
            $credentials = session('credentials');
            
            if (!$secondAuthenticationCode) {
                return redirect()->back()->with('error', 'Registro no encontrado');
            }
            
            /* Debe ser un codigo tipo web (1) */
            if($codeInput == $codeDB and $codeTipoDB == 1){
                $secondAuthenticationCode->used = true; //status
                $secondAuthenticationCode->save();
                
                if (Auth::validate($credentials)) {
                    Auth::login(Auth::getLastAttempted());
                }
               
                return redirect()->route('dashboard');
            } else {
                if($codeTipoDB == 2) //movil
                {return redirect()->route('second_auth')->with('error', 'El código no es válido, verifica en tu mobil.');}                
                return redirect()->route('second_auth')->with('error', 'El código no es válido.');
            }
            
        } catch(\Exception $e){
            return redirect()->route('second_auth')->with('error', 'Error al registrar codigo: ' . $e->getMessage());
        }
    }

    protected function getIdUsuario($correo)
    {
        $id = (User::where('email', $correo)->first())->id;
        return $id;
    }

    protected function sendLoginCodeEmail($user, $code)
    {
            $loginCodeMail = new LoginCodeMail($code);
            Mail::to($user->email)->send($loginCodeMail);
    }

    protected function getCodeSecondAuthentication($id_usuario)
    {
        $userSecondAuth = (SecondAuthenticationCodes::where('id_usuario', $id_usuario)->first());

        if ($userSecondAuth !==  null) {
            $code = $userSecondAuth->code;
        }else{
            $code = 1;
        }
        
        return $code;
    }
}
