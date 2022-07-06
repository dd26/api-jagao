<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use App\Helpers\Helper;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::all();
        foreach ($coupons as $item) {
            if ($item->status == 1) {
                $item->actionsNew = array(
                    [
                        'title' => 'Editar',
                        'url'=> null,
                        'action' => 'edit',
                        'icon' => 'img:vectors/edit4.png',
                        'color' => 'primary'
                    ],
                    [
                        'title' => 'Inhabilitar',
                        'url'=> null,
                        'action' => 'changeStatusCoupon',
                        'icon' => 'img:vectors/trash1.png',
                        'color' => 'danger'
                    ],
                    [
                        'title' => 'Eliminar',
                        'url'=> null,
                        'action' => 'delete',
                        'icon' => 'img:vectors/trash1.png',
                        'color' => 'danger'
                    ]
                );
            } else {
                $item->actionsNew = array(
                    [
                        'title' => 'Editar',
                        'url'=> null,
                        'action' => 'edit',
                        'icon' => 'img:vectors/edit4.png',
                        'color' => 'primary'
                    ],
                    [
                        'title' => 'Habilitar',
                        'url'=> null,
                        'action' => 'changeStatusCoupon',
                        'icon' => 'img:vectors/trash1.png',
                        'color' => 'danger'
                    ],
                    [
                        'title' => 'Eliminar',
                        'url'=> null,
                        'action' => 'delete',
                        'icon' => 'img:vectors/trash1.png',
                        'color' => 'danger'
                    ]
                );
            }

            if ($item->type == 1) {
                $item->discount = $item->value . '%';
            } else {
                $item->discount = '$' . $item->value;
            }
        };
        return response()->json($coupons);
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
        $coupon = new Coupon;
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->status = 1; // active
        $coupon->expiry_date = $request->expiry_date;
        $coupon->limit = $request->limit;
        $coupon->save();
        return response()->json($coupon);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $coupon = Coupon::find($id);
        return response()->json($coupon);
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
        $coupon = Coupon::find($id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        // $coupon->status = $request->status;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->limit = $request->limit;
        $coupon->save();
        return response()->json($coupon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return response()->json($coupon);
    }

    public function updateStatus($id, $status)
    {
        $coupon = Coupon::find($id);
        $coupon->status = $status;
        $coupon->save();
        return response()->json($coupon);
    }

    public function checkCouponByCode ($code) {
        $verifyCoupon = Helper::checkCoupon($code);
        return response()->json($verifyCoupon);
    }
}
