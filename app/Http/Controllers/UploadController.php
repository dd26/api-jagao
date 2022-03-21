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
        return Response::download($path);
    }

    public function changeImage($folder, $id, Request $request)
    {
        // reemplazar imagen por la nueva
        $path = public_path().'/storage/'.$folder.'/'. $id .'/'.$id . '.jpeg';
        $image = $request->file('image');
        $image->move(public_path().'/storage/'.$folder.'/'. $id .'/', $id . '.jpeg');
        return Response::json(['success' => 'Imagen cambiada']);
    }
}
