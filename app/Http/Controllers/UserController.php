<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\{ User, Customer, Specialist };
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if ($user) {
            $user->api_token = Str::random(60);
            $user->save();
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
            // retornar error de usuario no encontrado
            return response()->json(['error' => 'Usuario no encontrado'], 401);
        }
    }

    public function mailVerify (Request $request) {
        $user = User::where('email', $request->email)->first();
        // return response()->json($request->email);
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
        $data = $request->only('email', 'password', 'userName', 'birthDate', 'city', 'country', 'discountCoupon', 'identification', 'name', 'phone', 'address');
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
            $specialist->user_id = $user->id;
            $specialist->save();
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

        if ($request->hasFile('fileID')) {
            $file = $request->file('fileID');
            $file = $request->fileID;
            // guardar imagen
            if ($isEmployee === "true") {
                $file->move(public_path().'/storage/specialists/'.$specialist->id, 'my_identification' . '.jpeg');
            } else {
                $file->move(public_path().'/storage/customers/'.$customer->id, 'my_identification' . '.jpeg');
            }
        }

        return response()->json($user, 201);
    }



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
        //
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
