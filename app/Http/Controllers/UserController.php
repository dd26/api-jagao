<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\{ User, Customer, Specialist, SpecialistService, Category };
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class UserController extends Controller
{

    public function verifiedUser (Request $request, $id)
    {
        $user = User::find($id);
        $user->verified = true;
        $user->save();
        return response()->json($user);
    }

    public function verifyToken (Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();
        if ($user) {
            return response()->json(['status' => 'success', 'user' => $user]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Token invalido']);
        }
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::user();
        $user->status = $request->status;
        $user->save();
        return response()->json(['status' => 'success']);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->where('password', $request->password)->with('role')->first();
        if ($user) {
            $user->api_token = Str::random(60);
            $user->save();
            $user->permissions = $user->role->permissions()->get();

            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 401);
        }
    }


    public function loginApp (Request $request) {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if ($user && ($user['role_id'] == 2 || $user['role_id'] == 3)) {
            $user->api_token = Str::random(60);
            $user->save();
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 401);
        }
    }

    public function mailVerify (Request $request) {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'message' => 'El correo ya esta registrado, intente con otro.',
                'status' => 'error'
            ]);
        } else {
            return response()->json([
                'message' => 'El correo esta disponible',
                'status' => 'success'
            ]);
        }
    }

    public function storeApp (Request $request) {
        $data = $request->only('email', 'password', 'userName', 'birthDate', 'city', 'country', 'discountCoupon', 'identification', 'name', 'phone', 'address', 'zip_code', 'category_id');
        $isEmployee = $request->input('isEmployee');
        $user = new User();
        if ($isEmployee == "true") {
            $user->role_id = 2;
        } else {
            $user->role_id = 3;
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->save();

        if ($isEmployee == "true") {
            $specialist = new Specialist();
            $specialist->userName = $data['userName'];
            $specialist->birthDate = $data['birthDate'];
            $specialist->identification = $data['identification'];
            $specialist->country_id = $data['country'];
            $specialist->city_id = $data['city'];
            $specialist->address = $data['address'];
            $specialist->discountCoupon = $data['discountCoupon'];
            $specialist->phone = $data['phone'];
            $specialist->zip_code = $data['zip_code'];
            $specialist->user_id = $user->id;
            $specialist->save();
            // save category
            $category = Category::find($data['category_id']);
            $specialistService = new SpecialistService();
            $specialistService->category_id = $data['category_id'];
            $specialistService->user_id = $user->id;
            $specialistService->category_name = $category->name;
            $specialistService->price = 0;
            $specialistService->has_document = 0;
            $specialistService->save();
        } else {
            $customer = new Customer();
            $customer->userName = $data['userName'];
            $customer->birthDate = $data['birthDate'];
            $customer->identification = $data['identification'];
            $customer->country_id = $data['country'];
            $customer->city_id = $data['city'];
            $customer->address = $data['address'];
            $customer->discountCoupon = $data['discountCoupon'];
            $customer->phone = $data['phone'];
            $customer->zip_code = $data['zip_code'];
            $customer->user_id = $user->id;
            $customer->save();
        }

        if ($request->hasFile('profileImg')) {
            $file = $request->file('profileImg');
            $file = $request->profileImg;
            // guardar imagen
            if ($isEmployee == "true") {
                $file->move(public_path().'/storage/specialists/'.$specialist->id, $specialist->id . '.jpeg');
            } else {
                $file->move(public_path().'/storage/customers/'.$customer->id, $customer->id . '.jpeg');
            }
        }

        if ($request->hasFile('fileEmployee')) {
            $file = $request->file('fileEmployee');
            $file = $request->fileEmployee;
            // guardar imagen
            if ($isEmployee == "true") {
                $file->move(public_path().'/storage/specialists/'.$specialist->id,  'my_cv' . '.pdf');
            } else {
                $file->move(public_path().'/storage/customers/'.$customer->id, 'my_cv' . '.pdf');
            }
        }

        /* if ($request->hasFile('fileID')) {
            $file = $request->file('fileID');
            $file = $request->fileID;
            // guardar imagen
            if ($isEmployee === "true") {
                $file->move(public_path().'/storage/specialists/'.$specialist->id, 'my_identification' . '.jpeg');
            } else {
                $file->move(public_path().'/storage/customers/'.$customer->id, 'my_identification' . '.jpeg');
            }
        } */

        return response()->json($user, 201);
    }

    public function getUserInfo (Request $request)
    {
        $user = $request->user();
        if ($user->role_id == 2) {
            $specialist = Specialist::where('user_id', $user->id)->first();
            $specialist->user = $user;
            $specialist->specialistServices = $user->specialistServices;
            return response()->json($specialist);
        } else if ($user->role_id == 3) {
            $customer = Customer::where('user_id', $user->id)->first();
            $customer->user = $user;
            return response()->json($customer);
        }
    }

    // CRUD USER_ADMIN
    public function indexAdmin (Request $request) {
        $users = User::where('role_id', 4)->get();
        foreach ($users as $item) {
            if ($item->status === 1) {
                $item->actions = array(
                    [
                        'title' => 'Ver Detalles',
                        'url'=> null,
                        'action' => 'seeDetail',
                        'icon' => 'img:vectors/show1.svg',
                        'color' => 'primary'
                    ],
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
                        'title' => 'Deshabilitar',
                        'url'=> null,
                        'action' => 'changeStatusUserAdm',
                        'icon' => 'lock',
                        'color' => 'negative',
                        'type' => 'toggle'
                    ]
                );
            } else {
                $item->actions = array(
                    [
                        'title' => 'Ver Detalles',
                        'url'=> null,
                        'action' => 'seeDetail',
                        'icon' => 'img:vectors/show1.svg',
                        'color' => 'primary'
                    ],
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
                        'title' => 'Habilitar',
                        'url'=> null,
                        'action' => 'changeStatusUserAdm',
                        'icon' => 'lock_open',
                        'color' => 'positive',
                        'type' => 'toggle'
                    ]
                );
            }
        };
        return response()->json($users);
    }

    public function updateStatusUserAdm ($id) {
        $user = User::find($id);
        if ($user->status == 1) {
            $user->status = 0;
        } else {
            $user->status = 1;
        }
        $user->save();
        return response()->json($user);
    }

    public function showAdmin (Request $request, $id) {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 402);
        }
    }

    public function storeAdmin (Request $request) {
        $data = $request->only('email', 'password', 'name');
        $user = User::where('email', $data['email'])->first();
        if ($user) {
            return response()->json(['message' => 'El correo ya esta registrado'], 402);
        } else {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->role_id = 4;
            $user->save();


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file = $request->image;
                $file->move(public_path().'/storage/users_admin/'.$user->id, $user->id . '.jpeg');
            }

            return response()->json($user, 201);
        }
    }

    //destroyAdmin
    public function destroyAdmin (Request $request, $id) {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            // llamo al helper para eliminar un archivo
            Helper::deleteFile($user->id.'.jpeg', 'users_admin/'.$user->id);
            return response()->json(['message' => 'Usuario eliminado'], 200);
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 402);
        }
    }

    //updateAdmin
    public function updateAdmin (Request $request, $id) {
        // validar si se cambio el email y validar si ya existe
        $data = $request->only('email', 'password', 'name');
        $user = User::find($id);
        if ($user) {
            $email = $user->email;
            // validar email
            if ($email != $data['email']) {
                $userValidate = User::where('email', $data['email'])->first();
                if ($userValidate) {
                    return response()->json(['message' => 'El correo ya esta registrado'], 402);
                }
            }
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->save();
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 402);
        }
    }


}
