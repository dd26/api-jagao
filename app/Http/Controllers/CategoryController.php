<?php

namespace App\Http\Controllers;

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
        foreach ($categories as $item) {
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
        return response()->json($categories);
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
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json($category);
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
        $categories = Category::whereNotIn('id', $categoriesSpecialist->pluck('category_id'))->get();
        return response()->json($categories);
    }
}
