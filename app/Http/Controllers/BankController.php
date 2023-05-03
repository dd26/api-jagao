<?php

namespace App\Http\Controllers;
use App\Bank;

use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $banks = Bank::where('user_id', $request->user()->id)->get();
        return response()->json($banks);
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
        $user = $request->user();
        $bank = new Bank();
        $bank->user_id = $user->id;
        $bank->exterior_bank_id = $request->bank;
        $bank->account_number = $request->accountNumber;
        $bank->account_type = $request->accountType;
        $bank->full_name = $request->fullName;
        $bank->route_number = $request->routeNumber;
        $bank->save();
        return response()->json($bank);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank = Bank::find($id);
        return response()->json($bank);
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
        $bank = Bank::find($id);
        $bank->exterior_bank_id = $request->bank;
        $bank->account_number = $request->accountNumber;
        $bank->account_type = $request->accountType;
        $bank->full_name = $request->fullName;
        $bank->route_number = $request->routeNumber;
        $bank->save();
        return response()->json($bank);
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
