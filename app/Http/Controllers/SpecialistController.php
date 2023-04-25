<?php

namespace App\Http\Controllers;
use App\{Specialist, User, MasterRequestService, DetailRequestService, UserType};
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
            $specialist->cityName = $specialist->city->name;
            $specialist->verified = $user->verified;
            $specialist->isBlocked = $user->isBlocked;
            if ($specialist->user->isBlocked) {
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
                        'title' => '',
                        'url'=> null,
                        'action' => 'changeStatusDynamic',
                        'vueEmit' => true,
                        'icon' => 'lock',
                        'color' => 'negative',
                        'type' => 'toggleDynamic',
                        'field' => 'isBlocked',
                        'value' => 0
                    ],
                    [
                        'title' => 'Ver Detalles',
                        'url'=> null,
                        'action' => 'seeDetail',
                        'seeDetails' => 'true',
                        'icon' => 'img:vectors/trash1.png',
                    ]
                );
            } else {
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
                        'title' => '',
                        'url'=> null,
                        'action' => 'changeStatusDynamic',
                        'vueEmit' => true,
                        'icon' => 'lock',
                        'color' => 'negative',
                        'type' => 'toggleDynamic',
                        'field' => 'isBlocked',
                        'value' => 1
                    ],
                    [
                        'title' => 'Ver Detalles',
                        'url'=> null,
                        'action' => 'seeDetail',
                        'seeDetails' => 'true',
                        'icon' => 'img:vectors/trash1.png',
                    ],
                );
            }
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

        $userType = new UserType(['type' => 'specialist']);
        $user->userTypes()->save($userType);

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
        $specialist->city;
        $specialist->services = MasterRequestService::where('employee_id', $user->id)->get();
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

    public function profileUpdate (Request $request, $id)
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
        $user->save();

        return response()->json($specialist, 200);
    }

    public function getAmountFinish (Request $request)
    {
        $masterRequestService = MasterRequestService::where('employee_id', $request->user()->id)->where('state', 2)->get();
        $amount = 0;
        foreach ($masterRequestService as $masterRequest) {
            $detailRequestService = $masterRequest->detailRequestService;
            foreach ($detailRequestService as $detailRequest) {
                $amount += $detailRequest->service_price * $detailRequest->quantity;
            }
        }
        // mostrar decimales
        $amount = number_format($amount, 2, '.', ',');
        return response()->json($amount);
    }

    public function downloadCv($id)
    {
        // download file specialists
        $path = public_path().'/storage/specialists/'. $id .'/my_cv.pdf';
        if (file_exists($path)) {
            return Response::download($path);
        } else {
            return response()->json(['message' => 'No existe el archivo'], 404);
        }
    }

    public function changeStatusInBlocked (Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);
        $user->isBlocked = $request->input('isBlocked');
        $user->save();
        return response()->json($user);
    }
}
