<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Specialist, Notification, Customer };

class NotificationController extends Controller
{
    // index
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $notifications = Notification::where('user_id', $user_id)->get();
        // master request service relacion
        foreach ($notifications as $notification) {
            if ($notification->master_request_service_id) {
                $master_request_service = $notification->master_request_service;
                $employeeId = $master_request_service->employee_id;
                $employee = Specialist::where('user_id', $employeeId)->first();
                $notification->employee = $employee;
            }
        }
        return response()->json($notifications);
    }
}
