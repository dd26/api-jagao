<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\{ Category, SpecialistService };
use App\Helpers\Helper;
use File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json(CategoryResource::collection($categories));
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
        $category = new Category;
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->save();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            $file->move(public_path().'/storage/categories/'.$category->id, $category->id . '.jpeg');
        }
        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json(new CategoryResource($category));
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
        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->save();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            $file->move(public_path().'/storage/categories/'.$category->id, $category->id . '.jpeg');
        }
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        $file = public_path('storage/categories/'.$category->id.'/'.$category->id . '.jpeg');
        if (File::exists($file)) {
            unlink($file);
        }
        return response()->json($category);
    }

    // traer solo las categorias que no trabaje el empleado
    public function getCategoriesNotWorked(Request $request)
    {
        $categoriesSpecialist = SpecialistService::where('user_id', $request->user()->id);
        $categories = Category::where('status', 1)->whereNotIn('id', $categoriesSpecialist->pluck('category_id'))->get();
        return response()->json(CategoryResource::collection($categories));
    }

    // disableOrEnable
    public function disableOrEnable(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($category->status === 1) {
            $category->status = 0;
        } else {
            $category->status = 1;
        }
        $category->save();
        return response()->json($category);
    }

    //getCategoriesActives
    public function getCategoriesActives()
    {
        $categories = Category::where('status', 1)->with(['subcategories'])->get();
        return response()->json(CategoryResource::collection($categories));
    }
}
