<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        foreach ($addresses as $address) {
            $user = $address->user;
            $address->user = $user;
            $address->actions = array(
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
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $address = new Address();
        $address->user_id = $user->id;
        $address->name = request('name');
        $address->city_id = request('city_id');
        $address->address = request('address');
        $address->postal_code = request('postal_code');
        $address->save();
        return response()->json($address);
    }

    public function show($id)
    {
        $address = Address::find($id);
        $user = $address->user;
        $address->user = $user;
        return response()->json($address);
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);
        $address->name = request('name');
        $address->city_id = request('city_id');
        $address->address = request('address');
        $address->postal_code = request('postal_code');
        $address->save();
        return response()->json($address);
    }

    public function destroy($id)
    {
        $address = Address::find($id);
        $address->delete();
        return response()->json($address);
    }
}
