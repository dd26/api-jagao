<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', '9999999999');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Mail\TestMail;
use App\User;
use App\RecuperatePassword;

class MailController extends Controller
{
    public function sendMail ()
    {

        $data = [
            'name' => 'Binance Pay',
            'email' => 'denilsson.d.sousa@gmail.com',
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, quidem, temporibus, nisi, facere, soluta. Quidem, quisquam, temporibus, nisi, facere, soluta.',
            'subject' => 'ASUNTO',
        ];
        Mail::to('denilsson.d.sousa@gmail.com')->send(new TestMail($data));

    }

    public function sendMailRecuperatePassword (Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $code = Str::random(5);
            $data = [
                'name' => $user->name,
                'code' => $code,
                'email' => $user->email,
                'subject' => 'Recuperar contraseña - Jagao',
            ];

            $user->recuperatePasswords()->create([
                'code' => $code,
            ]);
            Mail::to($user->email)->send(new TestMail($data));
            return response()->json(['message' => 'Se ha enviado un email a ' . $user->email], 200);
        } else {
            return response()->json(['error' => 'El usuario no existe']);
        }
    }


    public function changePassword (Request $request)
    {
        $recuperatePassword = RecuperatePassword::where('code', $request->code)->first();
        if ($recuperatePassword) {
            $user = User::where('id', $recuperatePassword->user_id)->first();
            $user->password = $request->password;
            $user->save();
            $recuperatePassword->delete();
            return response()->json(['message' => 'Se ha cambiado la contraseña correctamente'], 200);
        } else {
            return response()->json(['error' => 'El código no es valido'], 200);
        }
    }

    public function sendMailRecuperatePasswordApp (Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // generar codigo de numeros aleatorios de 4 digitos
            $code = rand(1000, 9999);
            $data = [
                'name' => $user->name,
                'code' => $code,
                'email' => $user->email,
                'subject' => 'Recuperate password - Jagao',
            ];

            $user->recuperatePasswords()->create([
                'code' => $code,
            ]);
            Mail::to($user->email)->send(new TestMail($data));
            return response()->json(['message' => 'Se ha enviado un email a ' . $user->email], 200);
        } else {
            return response()->json(['error' => 'El usuario no existe']);
        }
    }

    // verify code
    public function verifyCode ($code)
    {
        $recuperatePassword = RecuperatePassword::where('code', $code)->first();
        if ($recuperatePassword) {
            return response()->json(['message' => 'El código es correcto'], 200);
        } else {
            return response()->json(['error' => 'the code is not valid'], 200);
        }
    }
}
