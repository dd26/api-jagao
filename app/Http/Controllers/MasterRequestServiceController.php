<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Card, MasterRequestService, DetailRequestService, Address, Category, SubCategory, Specialist, Notification, Customer, User, Coupon, CouponUse };
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;

class MasterRequestServiceController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $masterRequestService = new MasterRequestService;
        $masterRequestService->user_id = $user->id;
        $masterRequestService->address_id = $request->address_id;
        $masterRequestService->cvv = $request->cvv;
        $masterRequestService->card_id = $request->card_id;
        $masterRequestService->right_now = $request->right_now;

        $category = Category::find($request->category_id);
        $masterRequestService->category_id = $category->id;
        $masterRequestService->category_name = $category->name;
        $masterRequestService->fee = floatval(env('FEE'));

        if ($request->observations) {
            $masterRequestService->observations = $request->observations;
        } else {
            $masterRequestService->observations = '';
        }
        $masterRequestService->state = 0;
        if ($request->discount === 0) { // 0 = apply discount
            $code = $request->coupon;
            $verifyCoupon = Helper::checkCoupon($code);
            if ($verifyCoupon) {
                $masterRequestService->discount = 1;
                if ($verifyCoupon->type === 1) {
                    $totalAmount = Helper::getTotalByServices($request->services);
                    $discountAmount = $totalAmount * ($verifyCoupon->value / 100);
                } else {
                    $discountAmount = $verifyCoupon->value;
                }
                $masterRequestService->discount_amount = $discountAmount;
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Coupon not valid'
                ], 200);
            }
        } else {
            $masterRequestService->discount = 0;
            $masterRequestService->discount_amount = 0;
        }

        if (!$request->right_now) {
            $date = $request->date;
            $time = $request->time;
            $dateTime = $date . ' ' . $time;
            $masterRequestService->date_request = $dateTime;
        } else {
            $masterRequestService->date_request = null;
        }

        $masterRequestService->save();

        // save coupon uses
        if ($request->discount === 0) {
            $couponUse = new CouponUse;
            $couponUse->coupon_id = $verifyCoupon->id;
            $couponUse->master_request_service_id = $masterRequestService->id;
            $couponUse->save();
        }

        $services = $request->services;
        foreach ($services as $service) {
            $detailRequestService = new DetailRequestService;
            $detailRequestService->master_request_service_id = $masterRequestService->id;
            $detailRequestService->service_id = $service['id'];
            $detailRequestService->service_name = $service['name'];

            if ($service['description']) {
                $detailRequestService->service_description = $service['description'];
            } else {
                $detailRequestService->service_description = '';
            }

            $subCategory = SubCategory::find($service['id']);
            $detailRequestService->service_price = $subCategory->price;
            $detailRequestService->comision_app = $subCategory->comision_app;
            $detailRequestService->comision_espcialist = $subCategory->comision_espcialist;
            $detailRequestService->comision_is_porcentage = $subCategory->comision_is_porcentage;
            $detailRequestService->quantity = $service['quantity'];

            $detailRequestService->save();
        }

        try {
            $total = Helper::getTotalByServices($services);
            $total = $total + (float) env('FEE');
            if ($masterRequestService->discount === 1) {
                $total = $total - $masterRequestService->discount_amount;
            }
            Helper::createCharge($masterRequestService->card_id, $total, $masterRequestService->id);
            return response()->json(['message' => 'success', 'total' => $total], 200);
        }
        catch (\Exception $e) {
            $masterRequestService->delete();
            return response()->json(['error' => true, 'message' => $e->getMessage(), 'total' => $total], 200);
        }
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $masterRequestServices = MasterRequestService::where('user_id', $user->id)->where('state', 0)->get();
        foreach ($masterRequestServices as $masterRequestService) {
            $masterRequestService->address_name = $masterRequestService->address->name;
            $total = 0;
            foreach ($masterRequestService->detailRequestService as $detailRequestService) {
                if($detailRequestService->comision_is_porcentage){
                    $total += $detailRequestService->service_price * $detailRequestService->quantity;
                }else{
                    $total += ($detailRequestService->service_price + $detailRequestService->comision_espcialist + $detailRequestService->comision_app)* $detailRequestService->quantity;
                }
            }
            $total = $total + $masterRequestService->fee;
            $masterRequestService->total = $total;
        }
        return response()->json($masterRequestServices, 200);
    }

    //index by status
    public function indexByStatus(Request $request, $status)
    {
        $user = $request->user();
        $masterRequestServices = MasterRequestService::where('state', $status)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        foreach ($masterRequestServices as $masterRequestService) {
            $masterRequestService->address_name = $masterRequestService->address->name;
            $total = 0;
            foreach ($masterRequestService->detailRequestService as $detailRequestService) {
                if($detailRequestService->comision_is_porcentage){
                    $total += ($detailRequestService->service_price * ($detailRequestService->comision_espcialist / 100) ) * $detailRequestService->quantity;
                }else{
                    $total += ($detailRequestService->comision_espcialist)* $detailRequestService->quantity;
                }
            }

            $masterRequestService->total = $total;
        }
        return response()->json($masterRequestServices, 200);
    }


    public function indexByStatusAndCustomer(Request $request, $status)
    {
        $user = $request->user();
        $masterRequestServices = MasterRequestService::where('user_id', $user->id)->where('state', $status)->get();
        foreach ($masterRequestServices as $masterRequestService) {
            $masterRequestService->address_name = $masterRequestService->address->name;
            $total = 0;
            foreach ($masterRequestService->detailRequestService as $detailRequestService) {
                if($detailRequestService->comision_is_porcentage){
                    $total += $detailRequestService->service_price * $detailRequestService->quantity;
                }else{
                    $total += ($detailRequestService->service_price + $detailRequestService->comision_espcialist + $detailRequestService->comision_app)* $detailRequestService->quantity;
                }
            }
            $total = $total + $masterRequestService->fee;
            $masterRequestService->total = $total;
        }
        return response()->json($masterRequestServices, 200);
    }

    public function indexByStatusAndSpecialist(Request $request, $status)
    {
        $user = $request->user();
        $masterRequestServices = MasterRequestService::where('employee_id', $user->id)->where('state', $status)->get();
        foreach ($masterRequestServices as $masterRequestService) {
            $masterRequestService->address_name = $masterRequestService->address->name;
            $total = 0;
            foreach ($masterRequestService->detailRequestService as $detailRequestService) {
                if($detailRequestService->comision_is_porcentage){
                    $total += ($detailRequestService->service_price * ($detailRequestService->comision_espcialist / 100) ) * $detailRequestService->quantity;
                }else{
                    $total += $detailRequestService->comision_espcialist * $detailRequestService->quantity;
                }
            }
            $masterRequestService->total = $total;
        }
        return response()->json($masterRequestServices, 200);
    }


    public function show(Request $request, $id)
    {
        $masterRequestService = MasterRequestService::find($id);
        $masterRequestService->address_name = $masterRequestService->address->name;
        $total = 0;
        foreach ($masterRequestService->detailRequestService as $detailRequestService) {
            if ($request->filled('type')) {
                if($request->type == 'employee'){
                    if($detailRequestService->comision_is_porcentage){
                        $total += ($detailRequestService->service_price * ($detailRequestService->comision_espcialist / 100) ) * $detailRequestService->quantity;
                    }else{
                        $total += $detailRequestService->comision_espcialist * $detailRequestService->quantity;
                    }
                }else if($request->type == 'customer'){
                    if($detailRequestService->comision_is_porcentage){
                        $total += $detailRequestService->service_price * $detailRequestService->quantity;
                    }else{
                        $total += ($detailRequestService->service_price + $detailRequestService->comision_espcialist + $detailRequestService->comision_app)* $detailRequestService->quantity;
                    }
                }else{
                    $total += $detailRequestService->service_price * $detailRequestService->quantity;
                }
            }else{
                $total += $detailRequestService->service_price * $detailRequestService->quantity;
            }
        }
        if ($request->filled('type')) {
            if($request->type == 'customer'){
                $total +=  floatval(env('FEE'));
            }
        }
        $masterRequestService->total = $total;
        $masterRequestService->user = $masterRequestService->user;
        $customer = Customer::where('user_id', $masterRequestService->user_id)->first();
        $masterRequestService->customer = $customer;
        $specialist = Specialist::where('user_id', $masterRequestService->employee_id)->first();
        if ($specialist) {
            $masterRequestService->specialist = $specialist;
            $user_specialist = User::find($specialist->user_id);
            $masterRequestService->specialist->user = $user_specialist;
        } else {
            $masterRequestService->specialist = null;
        }
        return response()->json($masterRequestService, 200);
    }


    public function destroy(Request $request, $id)
    {
        $masterRequestService = MasterRequestService::find($id);
        if ($masterRequestService->state > 0) {
            Helper::generateNotification(
                $request->user()->name.' has canceled the service.',
                '',
                1,
                $masterRequestService->employee_id,
                $masterRequestService->id,
            );
        }
        $masterRequestService->state = 404;
        $masterRequestService->save();

        return response()->json(['message' => 'Solicitud de servicio eliminada correctamente'], 200);
    }

    public function updateStatus(Request $request, $id, $status)
    {
        $masterRequestService = MasterRequestService::find($id);
        $masterRequestService->state = $status;
        if ($status == 1) {
            $masterRequestService->employee_id = $request->user()->id;
            $estimateTime = $request->time;
            Helper::generateNotification(
                $request->user()->name.' has accepted your request for services.',
                'She will be at your house in approximately ' . $estimateTime,
                1,
                $masterRequestService->user_id,
                $masterRequestService->id,
            );
        }

        if ($status == 2) {
            Helper::generateNotification(
                $request->user()->name. ' has marked the service as finished',
                'press to go to the service and rate the service provider',
                1,
                $masterRequestService->user_id,
                $masterRequestService->id,
            );
        }

        $masterRequestService->save();
        return response()->json(['message' => 'Solicitud de servicio actualizada correctamente'], 200);
    }

    public function updateDateRequest(Request $request, $id)
    {
        $masterRequestService = MasterRequestService::find($id);
        $before = $masterRequestService->date_request;
        if ($request->right_now === false) {
            $date = $request->date;
            $time = $request->time;
            $dateTime = $date . ' ' . $time;
            $masterRequestService->date_request = $dateTime;
            $masterRequestService->right_now = 0;
        } else {
            $masterRequestService->date_request = null;
            $masterRequestService->right_now = 1;
        }
        $masterRequestService->save();

        if ($masterRequestService->state > 0) {
            $dates = [
                'before' => $before,
                'after' => $masterRequestService->date_request,
            ];
            $dates = json_encode($dates);

            Helper::generateNotification(
                'Customer '.$request->user()->name. ' has changed the service time',
                $dates,
                2,
                $masterRequestService->employee_id,
                $masterRequestService->id,
            );
        }

        return response()->json(['message' => 'Solicitud de servicio actualizada correctamente'], 200);
    }

}
