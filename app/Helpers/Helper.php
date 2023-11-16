<?php
namespace App\Helpers;
use File;
use Illuminate\Support\Facades\Log;

use App\{ Notification, Coupon, CouponUse, SubCategory, Card, Payment };



class Helper {
    //upload file
    public static function uploadImage($file, $pathModel) {
        $name = date('Ymd_His').'-'.$file->getClientOriginalName();
        // $file->move('image/'.$pathModel, $name);
        $file->move(public_path().'/storage/'.$pathModel.'/', $name);
        return $name;
    }

    //delete file
    public static function deleteFile($file, $pathModel) {
        $oldfile = public_path('storage/'.$pathModel.'/'.$file);
        if (File::exists($oldfile)) {
            unlink($oldfile);
        }
    }


    public static function generateNotification($title, $content, $type, $user_id, $master_request_service_id) {
        $notification = new Notification;
        $notification->user_id = $user_id;
        $notification->title = $title;
        $notification->content = $content;
        $notification->type = $type;
        $notification->viewed = 0;
        if ($master_request_service_id) {
            $notification->master_request_service_id = $master_request_service_id;
        }
        $notification->save();
    }


    public static function checkCoupon($code) {
        $coupon = Coupon::where('code', $code)->first();
        if ($coupon) {
            if ($coupon->status == 1 && $coupon->expiry_date > date('Y-m-d')) {
                $coupon_uses = CouponUse::where('coupon_id', $coupon->id)->get();
                $limit = $coupon->limit / $coupon->value;
                if ($coupon_uses->count() < $limit) {
                    return $coupon;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getTotalByServices($services) {
        $total = 0;
        foreach ($services as $service) {
            $subCategory = SubCategory::where('id', $service['id'])->first();
            if($subCategory->comision_is_porcentage === 1){
                $total += ($subCategory->price * ($subCategory->comision_espcialist / 100) ) * $service['quantity'];
            }else{
                $total += ($subCategory->price + $subCategory->comision_espcialist + $subCategory->comision_app)* $service['quantity'];
            }
        }
        return $total;
    }

    public static function createCharge($card_id, $amount, $master_request_service_id) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        $card = Card::find($card_id);
        $exp_month = substr($card->expiration_date, 0, 2);
        $exp_year = substr($card->expiration_date, -2);
        $exp_year = '20'.$exp_year;
        try {
            $token = $stripe->tokens->create([
                'card' => [
                    'number' => $card->number,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'cvc' => $card->cvv,
                ],
            ]);
        } catch (\Stripe\Error\Card $e) {
            return $e;
        }
        $amount = (number_format($amount, 2, '.', '') * 100);
        try {
            $charge = $stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'description' => 'Pago de Servicios JAGAO APP',
                'source' => $token['id'],
            ]);
        } catch (\Stripe\Error\Card $e) {
            return $e;
        }

        // save in payment table
        $payment = new Payment;
        $payment->master_request_service_id = $master_request_service_id;
        $payment->stripe_charge_id = $charge['id'];
        $payment->save();
        return $charge;
    }
}
