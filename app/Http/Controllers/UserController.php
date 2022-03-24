<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
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
            // retornar error de usuario no encontrado
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
