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
    public function generateCodeSecondAuthenticationCode(Request $request){
        
        $secondAuthenticationCode = new SecondAuthenticationCodes();
        
        try{
            $id_usuario = $this->getIdUsuario($request->email);
        
            $code = '12345'; //rand(10000,99999);

            $secondAuthenticationCode::updateOrInsert(
                ['id_usuario' => $id_usuario],
                [
                    'code' => hash('sha256', $code) ,
                    'id_usuario' => $id_usuario,
                    'used' => false,
                ]
            );

            $this->sendLoginCodeEmail($request, $code);

        } 
        catch(\Exception $e){

            //return redirect()->route('dashboard')->with('error', 'Error al registrar al nuevo usuario, vuelva a intentarlo mas tarde' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al registrar al nuevo usuario, vuelva a intentarlo mas tarde');
        }        
    }

    public function showViewSecondAuthCode(){
        return view('secondAuth.code-second-auth');
    }

    public function setSecondAuthCode(Request $request){
        try{

            $request->validate([
                'campo' => 'required|string|max:5|regex:{5}',
            ]);

            $id_usuario = $this->getIdUsuario(session('credentials.email'));
            $secondAuthenticationCode = SecondAuthenticationCodes::where('id_usuario', $id_usuario)->first();
            $codeInput = hash('sha256', $request->campo);
            $codeDB = $secondAuthenticationCode->code;
            //$user = User::where('email', session('credentials.email'))->first();

            $credentials = session('credentials');
            //dd($codeDB,$codeInput);

            if (!$secondAuthenticationCode) {
                return redirect()->back()->with('error', 'Registro no encontrado');
            }
            
            if($codeInput == $codeDB){
                $secondAuthenticationCode->used = true;
                $secondAuthenticationCode->save();
                
                if (Auth::validate($credentials)) {
                    Auth::login(Auth::getLastAttempted());
                }
                
                return redirect()->route('dashboard');
            }else {
                return redirect()->route('second_auth')->with('error', 'El código no es válido.');
            }
            
        } catch(\Exception $e){

            return redirect()->route('second_auth')->with('error', 'Error al actualizar o insertar el registro: ' . $e->getMessage());
        
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
