<?php

namespace App\Http\Controllers;
use App\{Specialist, User};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index()
    {
        $specialists = Specialist::all();
        foreach ($specialists as $specialist) {
            $user = $specialist->user;
            $specialist->email = $user->email;
            $specialist->actions = array(
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
                ],
                [
                    'title' => 'see details',
                    'url'=> null,
                    'action' => 'seeDetail',
                    'seeDetails' => 'true',
                    'icon' => 'img:vectors/trash1.png',
                ]
            );
        };
        return response()->json($specialists);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->input('userName');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->role_id = 2;
        $user->save();

        $specialist = new Specialist();
        $specialist->userName = $request->input('userName');
        $specialist->birthDate = $request->input('birthDate');
        $specialist->identification = $request->input('identification');
        $specialist->country_id = $request->input('country_id');
        $specialist->city_id = $request->input('city_id');
        $specialist->address = $request->input('address');
        $specialist->user_id = $user->id;
        $specialist->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            // guardar imagen
            $file->move(public_path().'/storage/specialists/'.$specialist->id, $specialist->id . '.jpeg');
        }
        return response()->json($specialist, 200);
    }

    public function show($id)
    {
        $specialist = Specialist::findOrFail($id);
        $user = $specialist->user;
        $specialist->email = $user->email;
        return response()->json($specialist);
    }

    public function update(Request $request, $id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->userName = $request->input('userName');
        $specialist->birthDate = $request->input('birthDate');
        $specialist->identification = $request->input('identification');
        $specialist->country_id = $request->input('country_id');
        $specialist->city_id = $request->input('city_id');
        $specialist->address = $request->input('address');
        $specialist->save();

        $user = User::findOrFail($specialist->user_id);
        $user->name = $request->input('userName');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();

        return response()->json($specialist, 200);
    }

    public function destroy($id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->delete();

        $user = User::findOrFail($specialist->user_id);
        $user->delete();
        return response()->json(['message' => 'Specialist deleted successfully'], 201);
    }

    public function specialistByUserId($id)
    {
        $specialist = Specialist::where('user_id', $id)->first();
        return response()->json($specialist);
    }

    public function profileUpdate ($id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->userName = $request->input('userName');
        $specialist->birthDate = $request->input('birthDate');
        $specialist->identification = $request->input('identification');
        $specialist->country_id = $request->input('country_id');
        $specialist->city_id = $request->input('city_id');
        $specialist->address = $request->input('address');
        $specialist->phone = $request->input('phone');
        $specialist->save();

        $user = User::findOrFail($specialist->user_id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();

        return response()->json($specialist, 200);
    }
}
