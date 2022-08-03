<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{SubCategory, Category, SpecialistService};
use App\Helpers\Helper;
use File;

class SpecialistServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $specialist_services = SpecialistService::where('user_id', $user_id)->get();
        return response()->json($specialist_services);
    }

    public function specialistServicesByCategory(Request $request, $category_id)
    {
        $user_id = $request->user()->id;
        $specialist_services = SpecialistService::where('user_id', $user_id)->where('category_id', $category_id)->get();
        return response()->json($specialist_services);
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
    public function store(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        $specialist_service = new SpecialistService;
        $specialist_service->category_name = $category->name;
        $specialist_service->category_id = $category_id;
        $specialist_service->user_id = $request->user()->id;
        $specialist_service->has_document = 0;
        $specialist_service->save();
        return response()->json($specialist_service);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        $specialist_service = SpecialistService::where('category_id', $category_id)->first();
        $specialist_service->delete();
        return response()->json($specialist_service);
    }
}
