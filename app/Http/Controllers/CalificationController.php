<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calification;

class CalificationController extends Controller
{

    public function store(Request $request, $master_request_service_id)
    {
        $user = $request->user();
        $calification = Calification::where('user_id', $user->id)->where('master_request_service_id', $master_request_service_id)->first();
        if ($calification) {
            return response()->json([
                'error' => true,
                'message' => 'You already calified this request'
            ], 200);
        } else {
            $calification = new Calification();
            $calification->user_id = $user->id;
            $calification->master_request_service_id = $master_request_service_id;
            $calification->rating = $request->rating;
            $calification->comment = $request->comment;
            $calification->save();
            return response()->json($calification);
        }
    }

    public function show(Request $request, $master_request_service_id)
    {
        $user = $request->user();
        $calification = Calification::where('user_id', $user->id)->where('master_request_service_id', $master_request_service_id)->first();
        return response()->json($calification);
    }
}
