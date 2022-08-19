<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Service, User, MasterRequestService, DetailRequestService, Address };
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = MasterRequestService::all();
        foreach ($services as $service) {
            $service->actions = array(
                [
                    'title' => 'Ver Detalles',
                    'url'=> null,
                    'action' => 'seeDetail',
                    'icon' => 'img:vectors/show1.svg',
                ]
            );
            $service->rating;
            $detailRequestServices = $service->detailRequestService;
            $total = 0;
            foreach ($detailRequestServices as $detailRequestService) {
                $total += $detailRequestService->quantity * $detailRequestService->service_price;
            }
            $service->total = $total;
            $service->subservicesCount = $service->detailRequestService->count();

            $serviceState = $service->state;
            if ($serviceState === 0) {
                $service->stateName = 'Pendiente';
            } else if ($serviceState === 1) {
                $service->stateName = 'En Proceso';
            } else if ($serviceState === 2) {
                $service->stateName = 'Finalizado';
            } else if ($serviceState === 404) {
                $service->stateName = 'Cancelado';
            }

            $service->addressName = $service->address->name;

        };
        return response()->json($services);
    }

    public function getImage($id)
    {
        $path = public_path().'/storage/categories/'. $id;
        if (file_exists($path)) {
            return Response::download($path);
        } else {
            $none = public_path().'/avatar4.png';
            return Response::download($none);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new Service();
        $service->name = $request->input('name');
        $service->price = $request->input('price');
        $service->description = $request->input('description');
        $service->category_id = $request->input('category_id');
        $service->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            $file->move(public_path().'/storage/services/'.$service->id, $service->id . '.jpeg');
        }
        return response()->json($service, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = MasterRequestService::findOrFail($id);
        $service->detailRequestService;
        $service->address;
        $service->user->customer;
        if ($service->employee_id !== null) {
            $service->employee->specialist;
        }
        $service->rating;

        $total = 0;
        foreach ($service->detailRequestService as $detailRequestService) {
            $total += $detailRequestService->service_price * $detailRequestService->quantity;
        }
        $service->total = $total;
        return response()->json($service);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->name = $request->input('name');
        $service->price = $request->input('price');
        $service->description = $request->input('description');
        $service->category_id = $request->input('category_id');
        $service->save();
        return $service;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return $service;
    }
}
