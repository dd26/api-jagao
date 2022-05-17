<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class UploadController extends Controller
{
    public function getImage($folder, $id)
    {
        $path = public_path().'/storage/'.$folder.'/'. $id .'/'.$id . '.jpeg';
        if (file_exists($path)) {
            return Response::download($path);
        } else {
            $none = public_path().'/avatar4.png';
            return Response::download($none);
        }
    }

    public function changeImage($folder, $id, Request $request)
    {
        $path = public_path().'/storage/'.$folder.'/'. $id .'/'.$id . '.jpeg';
        $image = $request->file('image');
        $image->move(public_path().'/storage/'.$folder.'/'. $id .'/', $id . '.jpeg');
        return Response::json(['success' => 'Imagen cambiada']);
    }

    public function getImageTwo($folder, $name)
    {
        $path = public_path().'/storage/'.$folder.'/'. $name;
        if (file_exists($path)) {
            return Response::download($path);
        } else {
            $none = public_path().'/avatar4.png';
            return Response::download($none);
        }
    }
}
