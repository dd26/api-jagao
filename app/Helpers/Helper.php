<?php
namespace App\Helpers;
use File;

use App\{ Notification, Coupon, CouponUse, SubCategory };



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
            $subCategory = SubCategory::find($service['id']);
            $total += $subCategory->price * $service['quantity'];
        }
        return $total;
    }
}
