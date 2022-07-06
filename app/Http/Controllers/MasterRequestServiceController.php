<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Card, MasterRequestService, DetailRequestService, Address, Category, SubCategory, Specialist, Notification, Customer, User, Coupon, CouponUse };
use App\Helpers\Helper;

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

        if ($request->observations) {
            $masterRequestService->observations = $request->observations;
        } else {
            $masterRequestService->observations = '';
        }
        $masterRequestService->state = 0;
        $masterRequestService->discount = $request->discount;

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
        if ($masterRequestService->discount === 1) {
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
            $detailRequestService->quantity = $service['quantity'];

            $detailRequestService->save();
        }
        return response()->json(['message' => 'success'], 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $masterRequestServices = MasterRequestService::where('user_id', $user->id)->where('state', 0)->get();
        foreach ($masterRequestServices as $masterRequestService) {
            $masterRequestService->address_name = $masterRequestService->address->name;
            $total = 0;
            foreach ($masterRequestService->detailRequestService as $detailRequestService) {
                $total += $detailRequestService->service_price * $detailRequestService->quantity;
            }
            $masterRequestService->total = $total;
        }
        return response()->json($masterRequestServices, 200);
    }

    //index by status
    public function indexByStatus(Request $request, $status)
    {
        $user = $request->user();
        $masterRequestServices = MasterRequestService::where('state', $status)->get();
        foreach ($masterRequestServices as $masterRequestService) {
            $masterRequestService->address_name = $masterRequestService->address->name;
            $total = 0;
            foreach ($masterRequestService->detailRequestService as $detailRequestService) {
                $total += $detailRequestService->service_price * $detailRequestService->quantity;
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
                $total += $detailRequestService->service_price * $detailRequestService->quantity;
            }
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
                $total += $detailRequestService->service_price * $detailRequestService->quantity;
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
            $total += $detailRequestService->service_price * $detailRequestService->quantity;
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
        $masterRequestService->delete();
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
}
