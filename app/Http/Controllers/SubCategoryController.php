<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubCategory;
use App\Category;
use App\Helpers\Helper;
use File;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // traer las subcategorias relacionadas con las categorias
        $subcategories = SubCategory::with('category')->get();
        foreach ($subcategories as $item) {
            $item->category_name = $item->category->name;
            $item->actions = array(
                [
                    'title' => 'Editar',
                    'url'=> null,
                    'action' => 'edit',
                    'icon' => 'img:vectors/edit4.png',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Eliminar',
                    'url'=> null,
                    'action' => 'delete',
                    'icon' => 'img:vectors/trash1.png',
                ]
            );
        };
        return response()->json($subcategories);
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
        $subcategory = new SubCategory;
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->description = $request->description;
        $subcategory->has_document = $request->has_document;
        $subcategory->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            $file->move(public_path().'/storage/subcategories/'.$subcategory->id, $subcategory->id . '.jpeg');
        }
        return response()->json($subcategory);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subcategory = SubCategory::find($id);
        return response()->json($subcategory);
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
     * Update the specified resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::find($id);
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->description = $request->description;
        $subcategory->has_document = $request->has_document;
        $subcategory->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            $file->move(public_path().'/storage/subcategories/'.$subcategory->id, $subcategory->id . '.jpeg');
        }
        return response()->json($subcategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = SubCategory::find($id);
        $subcategory->delete();

        $file = public_path('storage/subcategories/'.$subcategory->id.'/'.$subcategory->id . '.jpeg');
        if (File::exists($file)) {
            unlink($file);
        }

        return response()->json($subcategory);
    }

}
