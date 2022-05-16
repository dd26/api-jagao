<?php
namespace App\Helpers;
use File;

class Helper {
    //upload file
    public static function uploadImage($file, $pathModel) {
        $name = date('Ymd_His').'-'.$file->getClientOriginalName();
        $file->move('image/'.$pathModel, $name);
        return $name;
    }

    //delete file
    public static function deleteFile($file, $pathModel) {
        $oldfile = public_path('image/'.$pathModel.'/'.$file);
        if (File::exists($oldfile)) {
            unlink($oldfile);
        }
    }
}
