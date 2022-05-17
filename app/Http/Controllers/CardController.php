<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;


class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // traer todas las tarjetas por user_id
        $cards = Card::where('user_id', $request->user()->id)->get();
        return $cards;
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
        $card = new Card;
        $card->name = $request->name;
        $card->number = $request->cardNumber;
        $card->expiration_date = $request->expiredDate;
        $card->cvv = $request->securityCode;
        $card->postal_code = $request->postalCode;
        $card->user_id = $user->id;
        $card->save();
        return response()->json($card);
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
        $card = Card::find($id);
        $card->delete();
        return response()->json($card);
    }
}
