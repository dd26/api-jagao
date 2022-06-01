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
    public function index()
    {
        //
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

        // stringy to array
        $subcategoriesArray1 = $request->subcategories;
        $subcategoriesArray = json_decode($subcategoriesArray1);

        foreach ($subcategoriesArray as $subcategoryItem) {
            // busco la subcategoria
            $subcategory = SubCategory::find($subcategoryItem->id);
            // busco la categoria
            $category = Category::find($subcategory->category_id);
            // creo el servicio
            $specialistService = new SpecialistService();
            $specialistService->category_id = $category->id;
            $specialistService->category_name = $category->name;
            $specialistService->subcategory_id = $subcategory->id;
            $specialistService->subcategory_name = $subcategory->name;
            $specialistService->user_id = $request->user()->id;
            $specialistService->price = $subcategory->price;
            $specialistService->has_document = $subcategory->has_document;
            $specialistService->save();

            // si tiene documento, lo subo
            if ($subcategory->has_document) {
                $file = $request['documentFile'.$subcategory->id];
                $file->move(public_path().'/storage/specialist_services/'.$specialistService->id, $specialistService->id . '.jpeg');
            }
        }
        return response()->json($subcategoriesArray);
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
    public function destroy($id)
    {
        //
    }
}
