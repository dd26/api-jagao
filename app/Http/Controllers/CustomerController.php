<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Customer, User, MasterRequestService, DetailRequestService, UserType, RoleChangeRequest};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $customers = Customer::all();
        foreach ($customers as $customer) {
            $user = $customer->user;
            $customer->email = $user->email;
            $customer->actions = array(
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
                    'title' => 'Ver Detalle',
                    'url'=> null,
                    'action' => 'seeDetail',
                    'seeDetails' => 'true',
                    'icon' => 'img:vectors/trash1.png',
                ]
            );
        };
        return response()->json($customers);
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
        $user = new User();
        $user->name = $request->input('userName');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->role_id = 2;
        $user->save();

        $customer = new Customer();
        $customer->userName = $request->input('userName');
        $customer->birthDate = $request->input('birthDate');
        $customer->identification = $request->input('identification');
        $customer->country_id = $request->input('country_id');
        $customer->city_id = $request->input('city_id');
        $customer->address = $request->input('address');
        $customer->user_id = $user->id;
        $customer->save();

        $userType = new UserType(['type' => 'customer']);
        $user->userTypes()->save($userType);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file = $request->image;
            // guardar imagen
            $file->move(public_path().'/storage/customers/'.$customer->id, $customer->id . '.jpeg');
        }
        return response()->json($customer, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $user = $customer->user;
        $customer->email = $user->email;
        $customer->services = MasterRequestService::where('user_id', $user->id)->get();
        return response()->json($customer);
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
        $customer = Customer::findOrFail($id);
        $customer->userName = $request->input('userName');
        $customer->birthDate = $request->input('birthDate');
        $customer->identification = $request->input('identification');
        $customer->country_id = $request->input('country_id');
        $customer->city_id = $request->input('city_id');
        $customer->address = $request->input('address');
        $customer->save();

        $user = User::findOrFail($customer->user_id);
        $user->name = $request->input('userName');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();

        return response()->json($customer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        $user = User::findOrFail($customer->user_id);
        $user->delete();
        return response()->json(['message' => 'Customer deleted successfully'], 201);
    }

    public function customerByUserId ($id) {
        $customer = Customer::where('user_id', $id)->first();
        return response()->json($customer);
    }

    public function profileUpdate (Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->userName = $request->input('userName');
        $customer->birthDate = $request->input('birthDate');
        $customer->identification = $request->input('identification');
        $customer->country_id = $request->input('country_id');
        $customer->city_id = $request->input('city_id');
        $customer->address = $request->input('address');
        $customer->phone = $request->input('phone');
        $customer->save();

        $user = User::findOrFail($customer->user_id);
        $user->name = $request->input('name');
        $user->save();

        return response()->json($customer, 200);
    }

    public function customerToSpecialist (Request $request) {
        // Validación de la solicitud
        /* $request->validate([
            'categories' => 'required|array',
            'resume' => 'required|mimes:pdf',
            'identity_document' => 'required|mimes:pdf,jpeg,png',
        ]); */

        // Almacenamiento de archivos en el sistema de archivos (ejemplo utilizando el disco 'public')
        $userId = $request->user()->id;
        $resumeFileName = 'resume_' . time() . '.' . $request->resume->getClientOriginalExtension();
        $identityDocumentFileName = 'identity_document_' . time() . '.' . $request->identity_document->getClientOriginalExtension();

        $storagePath = 'public/storage/change_request_role_to_specialist/' . $userId;

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $file = $request->resume;
            // guardar imagen
            $file->move(public_path().'/storage/change_request_role_to_specialist/'.$userId, $resumeFileName);
        }

        if ($request->hasFile('identity_document')) {
            $file = $request->file('identity_document');
            $file = $request->identity_document;
            // guardar imagen
            $file->move(public_path().'/storage/change_request_role_to_specialist/'.$userId, $identityDocumentFileName);
        }

        $resumeFilePath = $storagePath . '/' . $resumeFileName;
        $identityDocumentFilePath = $storagePath . '/' . $identityDocumentFileName;

        // Creación de la nueva entrada en la tabla role_change_requests
        $roleChangeRequest = new RoleChangeRequest([
            'user_id' => $userId,
            'categories' => json_encode($request->categories),
            'resume' => $resumeFilePath,
            'identity_document' => $identityDocumentFilePath
        ]);

        $roleChangeRequest->save();

        return response()->json([
            'message' => 'Solicitud enviada correctamente',
            'request' => $roleChangeRequest
        ], 200);
    }
}
