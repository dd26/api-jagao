<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', '9999999999');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class MailController extends Controller
{
    public function sendMail ()
    {

        $data = [
            'name' => 'Binance Pay',
            'email' => 'denilsson.d.sousa@gmail.com',
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam, quidem, temporibus, nisi, facere, soluta. Quidem, quisquam, temporibus, nisi, facere, soluta.',
            'subject' => 'Lorem ipsum dolor sit amet ASUNTO',
        ];
        Mail::to('denilsson.d.sousa@gmail.com')->send(new TestMail($data));

    }

}
