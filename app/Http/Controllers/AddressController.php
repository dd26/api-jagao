<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Helpers\Helper;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)->get();
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
        $address->postal_code = request('postalCode');
        $address->save();

        if ($request->hasFile('image')) {
            if ($request->has('image')) {
                $path = Helper::uploadImage($request->image, 'address');
                $address->default_name_image = $path;
                $address->save();
            }

        };
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
        $address->postal_code = request('postalCode');
        $address->save();
        return response()->json($address);
    }

    public function destroy($id)
    {
        $address = Address::find($id);
        $address->delete();
        // eliminar la imagen si existe
        if ($address->default_name_image) {
            Helper::deleteFile($address->default_name_image, 'address');
        }
        return response()->json($address);
    }

    public function disableOrEnable($id)
    {
        $address = Address::find($id);
        if ($address->status == 1) {
            $address->status = 0;
        } else {
            $address->status = 1;
        }
        $address->save();
        return response()->json($address);
    }

    public function getAddressesByStatus (Request $request, $status)
    {
        $addresses = Address::where('user_id', $request->user()->id)->where('status', $status)->get();
        foreach ($addresses as $address) {
            $user = $address->user;
            $address->user = $user;
        };
        return response()->json($addresses);
    }

}
