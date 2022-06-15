<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Card, MasterRequestService, DetailRequestService, Address, Category, SubCategory };


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

        if ($request->discount) {
            $masterRequestService->discount_amount = $request->discount_amount;
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

        $services = $request->services;
        foreach ($services as $service) {
            $detailRequestService = new DetailRequestService;
            $detailRequestService->master_request_service_id = $masterRequestService->id;
            $detailRequestService->service_id = $service['id'];
            $detailRequestService->service_name = $service['name'];
            $detailRequestService->service_description = $service['description'];

            $subCategory = SubCategory::find($service['id']);
            $detailRequestService->service_price = $subCategory->price;
            $detailRequestService->quantity = $service['quantity'];

            $detailRequestService->save();
        }
        return response()->json(['message' => 'Solicitud de servicio creada correctamente'], 200);
    }
}
